<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config.php') ;

$cat = htmlspecialchars(trim($_GET['cat'])) ;
$query = $pdo->prepare('SELECT id_c, comment.author, username, comment.content, comment.date_published, parent_message, comment.signaled, id_m, message.title FROM comment
    LEFT JOIN users ON author = id_u
    LEFT JOIN message ON id_m = parent_message
    WHERE category = ?') ;
$success = $query->execute([$cat]) ;
if ($success = 0) {
  echo $success ;
  return ;
}
?>
<table class="table">
  <thead>
    <tr>
      <th scope="col">Article</th>
      <th scope="col">Auteur</th>
      <th scope="col">Contenu</th>
      <th scope="col">Signalement</th>
      <th scope="col">Supprimer</th>
    </tr>
  </thead>
  <tbody>
    <?php
    while ($comment = $query->fetch()) {
    ?>
    <tr class="<?= $comment['signaled'] == true ? 'table-warning' : '' ; ?>">
      <td><a href="/article/?post=<?= $comment['id_m'] ; ?>" target="_blank"><?= $comment['title'] ; ?></a></td>
      <td><a href="/user/?id=<?= $comment['author'] ; ?>" target="_blank"><?= $comment['username'] ; ?></a></td>
      <td scope="col"><?= substr($comment['content'], 0, 100) ; ?><a href="/article/?post=<?= $comment['id_m'] ; ?>#comment<?= $comment['id_c'] ; ?>" target="_blank"><small>… » Voir le commentaire</small></a></td>
      <td scope="col">
        <?php if ($comment['signaled'] == true) { ?>
        <button type="button" class="btn btn-success btn-sm" onclick="unsignalC(<?= $comment['id_c'] ; ?>, 'all')">Désignaler</button>
        <?php } else { ?>
        <button type="button" class="btn btn-outline-success btn-sm">√</button>
        <?php } ?>
      </td>
      <td scope="col">
        <button type="button" class="btn btn-outline-danger btn-sm" onclick="dropComment(<?= $comment['id_c'] ; ?>, 'all')">Supprimer</button>
      </td>
    </tr>
    <?php
    }
    ?>
  </tbody>
</table>
