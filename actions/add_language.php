<?php
require('../config.php') ;

$lang = htmlspecialchars($_POST['language']) ;

$q = $pdo->prepare('INSERT INTO language (lang) VALUES (:lang)') ;
$q->execute(['lang' => $lang]) ;

header('location: ../admin/languages.php') ;
exit ;
?>