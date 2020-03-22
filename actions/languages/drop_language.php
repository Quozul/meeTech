<?php
require('../../config.php') ; //require($_SERVER['DOCUMENT_ROOT'] . '/config.php') ;

if(!isset($_GET['lang']) || empty($_GET['lang'])) {
  header('location: /meetech/?error=unset') ; //header('location:' . $_SERVER['DOCUMENT_ROOT'] . '/admin/languages/?error=setlang') ;
  exit() ;
}

$language = htmlspecialchars($_GET['lang']) ;
$q = $pdo->prepare('DELETE FROM language WHERE lang = ?') ;
$q->execute([$language]);
header('location: /meetech/?success=drop') ; //header('location:' . $_SERVER['DOCUMENT_ROOT'] . '/admin/languages/') ;
exit() ;
?>
