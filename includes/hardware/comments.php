<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/includes/author_query.php');

$compid = isset($component['id']) ? $component['id'] : $_GET['id'];

$sth = $pdo->prepare('SELECT * FROM component_comment WHERE component = ?');
$sth->execute([$compid]);
$result = $sth->fetchAll();

if (count($result) > 0) {
    foreach ($result as $key => $comment) {
        $req = $pdo->prepare('SELECT username, avatar FROM users WHERE id_u = ?');
        $req->execute([$_SESSION['userid']]);
        $author = $req->fetch();
?>
        <div class="border-left border-dark pl-3">
            <div class="mt-avatar col-2 float-left mr-2" style="width: 48px; height: 48px; background-image: url('/uploads/<?php echo $author['avatar']; ?>');"></div>
            <div>
                <!-- TODO: Add user's profile link -->
                <h7 class="d-inline"><?php echo $author['username']; ?></h7>
                <small class="text-muted">
                    <?php
                    $d = new DateTime($comment['date_published']);
                    echo "Publié le " . $d->format('d M yy H:m');
                    if ($comment['date_edited'] != NULL) echo ", dernière édition le " . $comment['date_edited'];
                    ?>
                </small>
                <p><?php echo $comment['content']; ?></p>
            </div>
        </div>
    <?php }
} else { ?>
    <p class="lead">Aucun commentaire pour ce composant, soyez le premier à laisser un avis !</p>
<?php } ?>