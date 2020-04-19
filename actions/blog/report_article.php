<?php
require('../../config.php') ;

if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
  echo '-2' ;
  return ;
}

$id = htmlspecialchars(trim($_GET['article'])) ;

$query = $pdo->prepare('UPDATE message SET signaled = TRUE WHERE id_m = ?') ;
$success = $query->execute([$id]) ;

echo (int)$success ;
?>
