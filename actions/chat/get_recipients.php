<?php
require('../../config.php') ;
$channel = htmlspecialchars($_GET['chan']);

$query = $pdo->prepare('SELECT author, username, avatar FROM recipient INNER JOIN users ON id_u = author WHERE channel = ?') ;
$query->execute([$channel]) ;
$users = $query->fetchAll() ;

foreach($users as $u) { ?>
<a href="/user/?id=<?= $u['author'] ; ?>"><span class="badge badge-dark">
  <img src="/uploads/<?= $u['avatar'] ; ?>" alt="<?= $u['username'] ; ?>" class="mt-avatar" style="max-width: 32px; max-height: 32px;">
  <?= $u['username'] ; ?>
</span></a>
<?php } ?>
