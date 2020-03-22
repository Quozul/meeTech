<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config.php') ;

$language = htmlspecialchars($_GET['lang']) ;
$q = $pdo->prepare('DELETE FROM language WHERE lang = ?') ;
$q->execute([$language]);
header('location: ../../languages/?success=drop') ;
exit() ;
?>
