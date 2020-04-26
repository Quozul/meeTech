<?php
require('../../config.php');
$user = htmlspecialchars($_POST['username']);
$channel = htmlspecialchars($_POST['channel']);
//recup de l'id de l'utilisateur destinataire
$query = $pdo->prepare('SELECT id_u FROM users WHERE username = ?');
$query->execute([$user]);
$user = $query->fetch()['id_u'];
//Si l'utilisateur n'existe pas
if ($user == NULL) {
    echo '-1';
    return;
}

//Vérifier si l'utilisateur n'est pas déjà dans les destinataires du channel
$stmt = $pdo->prepare('SELECT author FROM recipient WHERE author = ? AND channel = ?');
$stmt->execute([$user, $channel]);
$exists = $stmt->fetch();

if ($exists == TRUE) {
    echo '-2';
    return;
}

//ajout nouvelle utilisateur
$sth = $pdo->prepare('INSERT INTO recipient (channel, author) VALUES (?, ?)');
$res = $sth->execute([$channel, $user]);

echo $res;
return;
