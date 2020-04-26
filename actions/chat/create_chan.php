<?php
require('../../config.php');

$name = htmlspecialchars($_POST['name']);
$user = htmlspecialchars($_POST['username']);

//recup de l'id de l'utilisateur destinataire
$query = $pdo->prepare('SELECT id_u FROM users WHERE username = ?');
$query->execute([$user]);
$user = $query->fetch()['id_u'];

// attribution chan auto --> chan
$sth = $pdo->prepare('INSERT INTO channel (name) VALUES(?)');
$sth->execute([$name]);

//recup du n° chan tout juste créé
$stmt = $pdo->query('SELECT MAX(id_c) FROM channel');
$count = $stmt->fetch()[0];

//ajout des utilisateurs dans recipients
$sth = $pdo->prepare('INSERT INTO recipient (channel, author) VALUES (?, ?)');
$s1 = $sth->execute([$count, $_SESSION['userid']]);
$s2 = $sth->execute([$count, $user]);

echo $count;
return;
