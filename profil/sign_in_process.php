<?php include ('../config.php');
$sth = $pdo->prepare('SELECT email, username FROM users WHERE username=? AND password=?');

$sth-> execute([$_POST[ 'username' ],hash('sha256', $_POST[ 'password' ]) ]);

$_SESSION['user'] = $sth->fetch();

var_dump($_SESSION['user']);
?>