<?php include('../../config.php');

$error = '';

$pseudo = htmlspecialchars($_POST['username']);
$password = hash('sha256', $_POST['password']);

// verify if user exists
$sth = $pdo->prepare('SELECT id_u, verified FROM users WHERE username=? AND password=?');
$sth->execute([$_POST['username'], hash('sha256', $_POST['password'])]);
$res = $sth->fetch();

// if password is wrong
if (empty($res))
    $error = $error . 'wrong_password;';
else
    // account isn't verified
    if (!$res['verified'])
        $error = $error . 'account_not_verified;';
    else
        $_SESSION['userid'] = $res[0];

if (!empty($error))
    echo 'ERROR\n' . $error;
