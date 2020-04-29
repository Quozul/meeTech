<?php
require('../../config.php') ;

$id = htmlspecialchars($_GET['post']) ;
$lang = htmlspecialchars($_GET['lang']) ;

$query = $pdo->prepare('SELECT content FROM translation WHERE original_message = ? AND language = ?') ;
$res = $query->execute([$id, $lang]) ;
$content = $query->fetch() ;
if ($content) {
  $content = $content['content'] ;
  echo $content ;
  return ;
}

$query = $pdo->prepare('SELECT content FROM message WHERE id_m = ?') ;
$res = $query->execute([$id]) ;
$content = $query->fetch()['content'] ;
echo $content ;
return ;
?>
