<?php
require('../../config.php') ;

if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
  echo '-2' ;
  return ;
}

$id = htmlspecialchars(trim($_GET['comm'])) ;

$query = $pdo->prepare('UPDATE comment SET signaled = TRUE WHERE id_c = ?') ;
$success = $query->execute([$id]) ;

echo $success ;
?>
