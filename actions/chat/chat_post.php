<?php

require('../../config.php');

$chan = htmlspecialchars(trim($_POST['chan']));
$message = htmlspecialchars(trim($_POST['message']));
$date = date('Y-m-d H:i:s');
// Insertion du message à l'aide d'une requête préparée
$req = $pdo->prepare('INSERT INTO private_message (author, content, date_published, channel) VALUES (?, ?, ?, ?)');
$success = $req->execute([$_SESSION['userid'], $message, $date, $chan]);

$query = $pdo->prepare('UPDATE recipient SET notif += 1 WHERE channel = ?') ;
$success2 = $query->execute([$chan]) ;

echo $success && $success2;
return;
