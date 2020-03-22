<?php

include('../../config.php');

$error = '';

$code = htmlspecialchars($_POST['code']);
$sth = $pdo->prepare('SELECT code FROM users WHERE email = ?');
$sth->execute([$code]);
$rec = $sth->fetch();

if ($code != $rec || !isset($code)) {
    $error = $error . "code_invalide";
    header('location: ../../lost_credentials_form/?error=' . $error);
    exit();
} else {
    header('location: ../../lost_credentials_form/');
}
