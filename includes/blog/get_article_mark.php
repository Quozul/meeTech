<?php
require('../../config.php') ;
$query = $pdo->prepare('SELECT COUNT(user) FROM vote_message WHERE message = ?') ;
$query->execute([$_POST['message_id']]);
$mark = ($query->fetch())[0] ;
echo $mark ;
return ;
?>
