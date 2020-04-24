<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config.php') ;

$cat = htmlspecialchars(trim($_GET['cat'])) ;
$query = $pdo->prepare('SELECT id_m, author, username, title, content, date_published, date_edited, default_language, icon, signaled FROM message
    LEFT JOIN users ON author = id_u
    LEFT JOIN language ON lang = default_language
    WHERE category = ? AND signaled = TRUE') ;
$success = $query->execute([$cat]) ;
if ($success = 0) {
  echo $success ;
  return ;
}

$stmt = $pdo->query('SELECT name FROM category') ;
$categories = $stmt->fetchAll() ;
?>
<table class="table">
  <thead>
    <tr>
      <th scope="col">Titre</th>
      <th scope="col">Auteur</th>
      <th scope="col">Contenu</th>
      <th scope="col">Déplacer</th>
      <th scope="col">Signalement</th>
      <th scope="col">Supprimer</th>
    </tr>
  </thead>
  <tbody>
    <?php
    while ($message = $query->fetch()) {
    ?>
    <tr class="table-warning">
      <td><a href="/article/?post=<?= $message['id_m'] ; ?>" target="_blank"><?= $message['title'] ; ?></a></td>
      <td><a href="/user/?id=<?= $message['author'] ; ?>" target="_blank"><?= $message['username'] ; ?></a></td>
      <td scope="col"><?= substr($message['content'], 0, 100) ; ?><a href="article/?post=<?= $message['id_m'] ; ?>" target="_blank"><small>… » Voir l'article</small></a></td>
      <td scope="col">
        <select onchange="moveToCat(<?= $message['id_m'] ; ?>, 'signaled')" id="newCat<?= $message['id_m'] ; ?>">
          <?php
          for ($i = 0 ; $i < count($categories) ; ++$i) {
            $name = $categories[$i]['name'] ;
          ?>
            <option value="<?= $name ; ?>" <?= $cat == $name ? 'selected' : '' ; ?>><?= $name ; ?></option>
          <?php } ?>
        </select>
      </td>
      <td scope="col">
        <?php if ($message['signaled'] == true) { ?>
        <button type="button" class="btn btn-success btn-sm">Désignaler</button>
        <?php } else { ?>
        <button type="button" class="btn btn-outline-success btn-sm">√</button>
        <?php } ?>
      </td>
      <td scope="col">
        <a href="#">
          <button type="button" class="btn btn-outline-danger btn-sm">Supprimer</button>
        </a>
      </td>
    </tr>
    <?php
    }
    ?>
  </tbody>
</table>
