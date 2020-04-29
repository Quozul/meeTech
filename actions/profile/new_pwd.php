<?php

include('../../config.php');

$code = htmlspecialchars($_POST['code']) ;
$error = '';

//Is set password and confirmation
if (!isset($_POST['new_pwd']) || !isset($_POST['confirm_pwd'])) {
    $error = $error . 'password_not_set;';
    header('location: ../../lost_credentials_form/?error=' . $error);
    exit() ;
}

//Both not empty
if (!empty($_POST['confirm-pwd']) || !empty($_POST['new_pwd'])) {
    $error = $error . 'confirm_password;';
    header('location: ../../lost_credentials_form/?error=' . $error);
    exit() ;
}

//Not equivalent
if ($_POST['new_pwd'] != $_POST['confirm-pwd']) {
    $error = $error . 'incorrect_password;';
    header('location: ../../lost_credentials_form/?error=' . $error);
    exit() ;
}

// Password min 8 char
if (strlen($_POST['password']) < 8) {
    $error = $error . 'password_too_short;';
    header('location: ../../lost_credentials_form/?error=' . $error);
}

$password = hash('sha256', $_POST['new_pwd']);

$sth = $pdo->prepare('UPDATE users SET password=? WHERE code = ?');
$sth->execute([$_POST['new_pwd'], $code]);
header('location: ../../lost_credentials_form/?success=password');
exit() ;
?>
