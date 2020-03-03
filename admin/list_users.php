<!DOCTYPE html>
<html>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'); ?>
<body>
<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php');

include('../config.php');

$sth = $pdo->prepare('SELECT username, email, avatar, prefered_language, location, bio FROM users'$sth = $pdo->prepare('SELECT * FROM users WHERE username = ?');
$sth->execute([$pseudo]);
$rec = $sth->fetch();
if (!empty($rec) && count($rec) > 0) {
    exit();}
$sth->execute([]);
$rec = $sth->fetchAll();

var_dump($rec); ?>
</body>
</html>

