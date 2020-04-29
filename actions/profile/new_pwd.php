<?php

include('../../config.php');

$code = htmlspecialchars($_POST['code']) ;

$error = '';
$password = hash('sha256', $_POST['new_pwd']);
// Password min 8 char
if (!isset($_POST['new_pwd']) || !isset($_POST['confirm_pwd'])) {
    $error = $error . 'password_not_set;';
    header('location: ../../lost_credentials_form/?=' . $error);
}

if (!empty($_POST['confirm-pwd']) || !empty($_POST['new_pwd'])) {
    $error = $error . 'confirm_password;';
    header('location: ../../lost_credentials_form/?=' . $error);
} else if ($_POST['new_pwd'] != $_POST['confirm-pwd']) {
    $error = $error . 'incorrect_password;';
    header('location: ../../lost_credentials_form/?=' . $error);
}

if (strlen($_POST['password']) < 8) {
    $error = $error . 'password_too_short;';
    header('location: ../../lost_credentials_form/?=' . $error);
}
if ($_POST['new_pwd'] == $_POST['confirm_pwd']) {
    $sth = $pdo->prepare('UPDATE users SET password=? WHERE code = ?');
    $sth->execute([$_POST['new_pwd'], $code]);
} else {
    $error = $error . 'Le_mot_de_passe_ne_corresponds_pas';
    header('location: ../../lost_credentials_form/?=' . $error);
}
