<?php
require('../../config.php') ;

if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
  echo '-2' ;
  return ;
}
if (!isset($_POST['commentContent']) || empty($_POST['commentContent'])
  || !isset($_POST['parentMessage']) || empty ($_POST['parentMessage'])
  || !isset($_POST['parentComment'])) {
  echo '-1' ;
  return ;
}

$content = htmlspecialchars(trim($_POST['commentContent'])) ;
$article = htmlspecialchars(trim($_POST['parentMessage'])) ;
$parent = htmlspecialchars(trim($_POST['parentComment'])) ;
$date = date('Y-m-d H:m') ;

$query = $pdo->prepare('INSERT INTO comment (author, parent_message, parent_comment, content, date_published)
      VALUES (:author, :parentM, :parentC, :content, :datePub)') ;
$result = $query->execute([
  'author' => $_SESSION['userid'],
  'parentM' => $article,
  'parentC' => $parent,
  'content' => $content,
  'datePub' => $date
]) ;
echo $result ;
?>
