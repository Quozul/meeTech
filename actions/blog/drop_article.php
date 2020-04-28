<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config.php') ;
$article = htmlspecialchars($_GET['post']) ;

$q = $pdo->prepare('SELECT category FROM message WHERE id_m = ?') ;
$q->execute([$article]) ;
$category = $q->fetch() ;
$category = $category['category'] ;

$delete = $pdo->prepare('DELETE FROM comment WHERE parent_message = ?') ;
$delete->execute([$article]) ;

$delete = $pdo->prepare('DELETE FROM file WHERE message = ?') ;
$delete->execute([$article]) ;

$delete = $pdo->prepare('DELETE FROM message WHERE id_m = ?') ;
$delete->execute([$article]) ;

header('location: /community/?cat=' . $category . '&success=drop') ;
exit() ;
?>
