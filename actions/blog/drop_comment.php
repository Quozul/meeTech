<?php
require('../../config.php') ;

if (!isset($_GET['comm']) || empty($_GET['comm'])) {
  echo '-1' ;
  return ;
}

$comment = htmlspecialchars(trim($_GET['comm'])) ;

$stmt = $pdo->prepare('SELECT id_c FROM comment WHERE parent_comment = ?') ;
$stmt->execute([$comment]) ;
$childs = $stmt->fetchAll() ;

if (count($childs) == 0) {
  $query = $pdo->prepare('DELETE FROM comment WHERE id_c = ? AND author = ?') ;
  $result = $query->execute([$comment, $_SESSION['userid']]) ;
} else {
  $query = $pdo->prepare('UPDATE comment SET content = ? WHERE id_c = ? AND author = ?') ;
  $result = $query->execute(['*Commentaire supprimé*', $comment, $_SESSION['userid']]) ;
}

echo $result ;
?>
