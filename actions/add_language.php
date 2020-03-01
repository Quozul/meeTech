<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config.php') ;

$lang = htmlspecialchars($_POST['language']) ;

$q = $pdo->prepare('INSERT INTO language (lang) VALUES (:lang)') ;
$q->execute(['lang' => $lang]) ;

header('location: ' . $_SERVER['DOCUMENT_ROOT'] . '/admin/languages.php/') ;
exit ;
?>