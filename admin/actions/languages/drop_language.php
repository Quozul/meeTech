<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config.php') ;

if(!isset($_GET['lang']) || empty($_GET['lang'])) {
  header('location: ../../languages/?error=setlang') ;
  exit() ;
}

$language = htmlspecialchars($_GET['lang']) ;
$q = $pdo->prepare('DELETE FROM language WHERE lang = ?') ;
$q->execute([$language]);
header('location: ../../languages/?success=drop') ;
exit() ;
?>
