<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config.php') ;

$comment = htmlspecialchars(trim($_GET['comm'])) ;
$query = $pdo->prepare('UPDATE comment SET signaled = FALSE WHERE id_c = ?') ;
$success = $query->execute([$comment]) ;

echo $success ;
return ;
?>
