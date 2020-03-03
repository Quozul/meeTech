<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/author_query.php');

$compid = isset($component['id']) ? $component['id'] : $_GET['id'];

$sth = $pdo->prepare('SELECT * FROM component_comment WHERE component = ?');
$sth->execute([$compid]);
$result = $sth->fetchAll();

if (count($result) > 0) {
    foreach ($result as $key => $comment) {
        $author = author_query($comment['author'], $pdo);
?>
        <div class="border-left border-dark pl-3">
            <!-- get image from user -->
            <h7 class="d-inline"><?php echo $author; ?></h7>
            <small class="text-muted">
                <?php
                echo "Publié le " . $comment['date_published'];
                if ($comment['date_edited'] != NULL) echo ", dernière édition le " . $comment['date_edited'];
                ?>
            </small>
            <p><?php echo $comment['content']; ?></p>
        </div>
    <?php }
} else { ?>
    <p class="lead">Aucun commentaire pour ce composant, soyez le premier à laisser un avis !</p>
<?php } ?>