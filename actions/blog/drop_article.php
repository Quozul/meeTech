<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config.php') ;
$article = htmlspecialchars($_GET['post']) ;
$q = $pdo->prepare('DELETE FROM message WHERE id_m = ?') ;
$q->execute([$article]) ;
header('location : /blog/?success=drop') ;
exit() ;
?>
