<?php include ('../config.php');
$sth = $pdo->prepare('SELECT id_user FORM user WHERE username=? AND password=?');

$sth-> execute([$_POST[ 'username' ],hash('sha256', $_POST[ 'password' ]) ]);

$_SESSION['id'] = $sth->fetch()[0];

?>