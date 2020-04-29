<?php
require('../../config.php') ;

$id = htmlspecialchars($_GET['post']) ;
$lang = htmlspecialchars($_GET['lang']) ;

$query = $pdo->prepare('SELECT title FROM translation WHERE original_message = ? AND language = ?') ;
$res = $query->execute([$id, $lang]) ;
$title = $query->fetch() ;
if ($title) {
  $title = $title['title'] ;
  echo $title ;
  return ;
}

$query = $pdo->prepare('SELECT title FROM message WHERE id_m = ?') ;
$res = $query->execute([$id]) ;
$title = $query->fetch()['title'] ;
echo $title ;
return ;
?>
