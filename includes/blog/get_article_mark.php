<?php
require('../../config.php') ;

$article = htmlspecialchars(trim($_GET['article'])) ;
$query = $pdo->prepare('SELECT COUNT(user) FROM vote_message WHERE message = ?') ;
$query->execute([$article]);

$mark = ($query->fetch())[0] ;
if ($mark == NULL) $mark = 0 ;
echo $mark ;
return ;
?>
