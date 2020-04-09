<?php
include_once('../../config.php') ;
$article = $_GET['post'] ;

$sth = $pdo->prepare('SELECT id_c, author, avatar, username, content, date_published, date_edited FROM comment
  LEFT JOIN users ON id_u = author
  WHERE parent_message = ? AND parent_comment = 0 ORDER BY date_published DESC') ;
$sth->execute([$article]) ;
$result = $sth->fetchAll() ;

function print_comment($pdo, $comment, $padding)
{
    // get children comments
    $req = $pdo->prepare('SELECT id_c, author, avatar, username, content, date_published, date_edited FROM comment
      LEFT JOIN users ON id_u = author
      WHERE parent_comment = ? ORDER BY date_published DESC') ;
    $req->execute([$comment['id_c']]) ;
    $child_comments = $req->fetchAll() ;
?>

    <div class="border-left border-dark p-1 mb-2 comment" style="margin-left: <?= $padding ; ?>px" id="comment-<?= $comment['id_c'] ; ?>">
        <div class="mt-avatar col-2 float-left mr-2" style="width: 48px ; height: 48px ; background-image: url('/uploads/<?= $author['avatar'] ; ?>') ;"></div>
        <div>
            <?php if ($comment['author'] != 0) { ?>
              <img src="/uploads/<?= $comment['avatar'] ; ?>" alt="<?= $comment['author'] ; ?>'s' profile picture" style="width:32px; height:32px;" class="mt-avatar float-left">
              <h7 class="d-inline comment-author"><a href="/user/?id=<?= $comment['author'] ; ?>"><?= $comment['username'] ; ?></a></h7>
            <?php } else { ?>
              <h7 class="d-inline comment-author">[Supprimé]</h7>
            <?php } ?>

            <small class="text-muted">
                <?php
                $dp = new DateTime($comment['date_published']) ;
                $de = new DateTime($comment['date_edited']) ;
                echo "Publié le " . $dp->format('d M yy à H:i') ;
                if ($comment['date_edited'] != NULL) echo ", dernière édition le " . $dp->format('d M yy à H:i') ;
                ?>
            </small>

            <?php if ($comment['author'] != 0) { ?>
                <small class="float-right badge badge-primary btn-sm mr-2" onclick="aswer_comment(<?= $comment['id_c'] ; ?>) ;">Répondre</small>
                <?php if ($comment['author'] != $_SESSION['userid']) { ?>
                    <small class="float-right badge badge-danger btn-sm mr-2">Signaler</small>
                <?php } else { ?>
                    <small class="float-right badge badge-danger btn-sm mr-2" onclick="remove_comment(<?= $comment['id_c'] ; ?>) ;">Supprimer</small>
                    <small class="float-right badge badge-secondary btn-sm mr-2">Modifier</small>
            <?php }
            } ?>

            <p class="mb-0"><?= $comment['content'] ; ?></p>
        </div>
    </div>

    <?php
    if (count($child_comments) > 0)
        foreach ($child_comments as $key => $child) {
            print_comment($pdo, $child, $padding + 25) ;
        }
}

if (count($result)) {
     foreach ($result as $key => $comment) {
        print_comment($pdo, $comment, 0) ;
    }
} else { ?>
     <p class="lead">Aucun commentaire pour cet article, soyez le premier à laisser un avis !</p>
<?php } ?>
