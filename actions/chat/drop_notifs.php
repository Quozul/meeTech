<?php
require('../../config.php') ;

$channel = htmlspecialchars($_GET['chan']) ;

$stmt = $pdo->prepare('UPDATE recipient SET notif = 0 WHERE channel = ? AND author = ?') ;
$success = $stmt->execute([$channel, $_SESSION['userid']]) ;

echo $success ;
return ;
?>
