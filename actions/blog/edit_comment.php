<?php
require('../../config.php') ;

if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
  echo '-2' ;
  return ;
}

if (!isset($_POST['comm']) || empty($_POST['comm'])
  || !isset($_POST['commentContent']) || empty ($_POST['commentContent'])) {
  echo '-1' ;
  return ;
}

$comment = htmlspecialchars(trim($_POST['comm'])) ;
$content = htmlspecialchars(trim($_POST['commentContent'])) ;
$date = date('Y-m-d H:i:s') ;

$query = $pdo->prepare('UPDATE comment SET content = :content, date_edited = :de WHERE id_c = :id AND author = :user') ;
$result = $query->execute([
  'content' => $content,
  'de' => $date,
  'id' => $comment,
  'user' => $_SESSION['userid']
]) ;
echo $result ;
?>
