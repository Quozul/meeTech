<?php include('../../config.php');
$sth = $pdo->prepare('SELECT id_u FROM users WHERE username=? AND password=?');

$sth->execute([$_POST['username'], hash('sha256', $_POST['password'])]);

$_SESSION['userid'] = $sth->fetch()[0];

var_dump($_SESSION);
header('location: /index.php');
