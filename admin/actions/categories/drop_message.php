<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config.php') ;
$article = htmlspecialchars($_GET['post']) ;

$delete = $pdo->prepare('DELETE FROM message WHERE id_m = ?') ;
$result = $delete->execute([$article]) ;

echo $result ;
return ;
?>
