<?php include('../../config.php');

$error = '';

// verify if username exists
$sth = $pdo->prepare('SELECT id_u FROM users WHERE username=?');
$sth->execute([$_POST['username']]);

if (empty($sth->fetch()))
    $error = $error . 'username_not_found;';

$sth = $pdo->prepare('SELECT id_u FROM users WHERE username=? AND password=?');
$sth->execute([$_POST['username'], hash('sha256', $_POST['password'])]);
$res = $sth->fetch();

// if password is wrong
if (empty($res))
    $error = $error . 'wrong_password;';

$_SESSION['userid'] = $res[0];

if (!empty($error))
    echo 'ERROR\n' . $error;
