<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config.php') ;

$cat = htmlspecialchars(trim($_GET['cat'])) ;
$id = htmlspecialchars(trim($_GET['mess'])) ;
$query = $pdo->prepare('UPDATE message SET category = (SELECT name FROM category WHERE name = :cat) WHERE id_m = :id') ;
$success = $query->execute([
  'cat' => $cat,
  'id' => $id
]) ;
echo $success ;
return ;
?>
