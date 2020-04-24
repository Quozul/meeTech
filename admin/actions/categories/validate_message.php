<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config.php') ;

$message = htmlspecialchars(trim($_GET['mess'])) ;
$query = $pdo->prepare('UPDATE message SET signaled = FALSE WHERE id_m = ?') ;
$success = $query->execute([$message]) ;

echo $success ;
return ;
?>
