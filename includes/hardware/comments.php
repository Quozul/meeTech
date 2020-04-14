<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/author_query.php');

$compid = $_GET['id'];

$sth = $pdo->prepare('SELECT * FROM component_comment WHERE component = ? AND parent_comment IS NULL ORDER BY date_published DESC');
$sth->execute([$compid]);
$result = $sth->fetchAll();

function print_comment($pdo, $comment, $padding = 0)
{
    // get author
    $req = $pdo->prepare('SELECT username, avatar FROM users WHERE id_u = ?');
    $req->execute([$comment['author']]);
    $author = $req->fetch();

    // get children comments
    $req = $pdo->prepare('SELECT * FROM component_comment WHERE parent_comment = ?');
    $req->execute([$comment['id_c']]);
    $child_comments = $req->fetchAll();
?>

    <div class="border-left border-dark p-1 mb-2 comment" style="margin-left: <?php echo $padding; ?>px" id="comment-<?php echo $comment['id_c']; ?>">
        <div class="mt-avatar col-2 float-left mr-2" style="width: 48px; height: 48px; background-image: url('/uploads/<?php echo $author['avatar']; ?>');"></div>
        <div>
            <?php if ($comment['author'] != 0) { ?>
                <h7 class="d-inline comment-author"><a href="/user/?id=<?php echo $comment['author']; ?>"><?php echo $author['username']; ?></a></h7>
            <?php } else { ?>
                <h7 class="d-inline comment-author">[Supprimé]</h7>
            <?php } ?>

            <small class="text-muted">
                <?php
                $dp = new DateTime($comment['date_published']);
                $de = new DateTime($comment['date_edited']);
                echo "Publié le " . $dp->format('d M yy à H:i');
                if ($comment['date_edited'] != NULL) echo ", dernière édition le " . $dp->format('d M yy à H:i');
                ?>
            </small>

            <?php if ($comment['author'] != 0) { ?>
                <small class="float-right btn btn-primary btn-sm mr-2" onclick="aswer_comment(<?php echo $comment['id_c']; ?>);">Répondre</small>
                <?php if (isset($_SESSION['userid']) && $comment['author'] != $_SESSION['userid']) { ?>
                    <small class="float-right btn btn-danger btn-sm mr-2">Signaler</small>
                <?php } else { ?>
                    <small class="float-right btn btn-danger btn-sm mr-2" onclick="remove_comment(<?php echo $comment['id_c']; ?>);">Supprimer</small>
                    <!-- <small class="float-right btn btn-secondary btn-sm mr-2">Modifier</small> -->
            <?php }
            } ?>

            <p class="mb-0"><?php echo $comment['content']; ?></p>
        </div>
    </div>

    <?php
    if (count($child_comments) > 0)
        foreach ($child_comments as $key => $child) {
            print_comment($pdo, $child, $padding += 10);
        }
}

if (count($result) > 0) {
    foreach ($result as $key => $comment) {
        print_comment($pdo, $comment);
    }
} else { ?>
    <p class="lead">Aucun commentaire pour ce composant, soyez le premier à laisser un avis !</p>
<?php } ?>