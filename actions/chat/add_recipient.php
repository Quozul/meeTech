<?php
$user = htmlspecialchars($_POST['username']);
$channel = htmlspecialchars($_POST['channel']);
//recup de l'id de l'utilisateur destinataire
$query = $pdo->prepare('SELECT id_u FROM users WHERE username = ?');
$query->execute([$user]);
$user = $query->fetch()['id_u'];
//ajout nouvelle utilisateur
$sth = $pdo->prepare('INSERT INTO recipient (channel, author) VALUES (?, ?)');
$res = $sth->execute([$chanel, $user]);

echo $res;
return;
