<?php

include('../../config.php');

$error = '';

$code = htmlspecialchars($_POST['code']);
$mail = htmlspecialchars($_POST['mail']) ;
$sth = $pdo->prepare('SELECT code FROM users WHERE email = ? AND code = ?');
$sth->execute([$mail, $code]);
$rec = $sth->fetch()[0];

if ($code != $rec || !isset($code)) {
    $error = $error . "code_invalide";
    header('location: ../../lost_credentials_form/?error=' . $error);
    exit();
} else {
    header('location: ../../lost_credentials_form/');
}
