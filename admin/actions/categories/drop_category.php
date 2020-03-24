<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config.php') ;

$name = htmlspecialchars($_GET['category']) ;
$q = $pdo->prepare('DELETE FROM category WHERE name = ?') ;
$q->execute([$name]);
header('location: ../../../categories/?success=drop') ;
exit() ;
?>
