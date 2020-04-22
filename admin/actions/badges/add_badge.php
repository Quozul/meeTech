<?php

include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$error = '';

$name = htmlspecialchars(trim($_POST['name']));
$description = htmlspecialchars(trim($_POST['description']));
$glbperm = htmlspecialchars(trim($_POST['global_perm']));
$obtention = htmlspecialchars(trim($_POST['obtention']));

if (!isset($_POST['name']))
    $error = $error . 'name_not_set;';

if (strlen($_POST['name']) > 20)
    $error = $error . 'name_too_long;';

$sth = $pdo->prepare('SELECT * FROM badge WHERE name = ?');
$sth->execute([$name]);
$rec = $sth->fetch();

if (!empty($rec) && count($rec) > 0)
    $error = $error . 'name_already_taken;';

if (!empty($error)) {
    echo 'ERROR\n' . $error;
    exit();
}

try {
    $sth = $pdo->prepare('INSERT INTO badge (name, description, global_permissions, img_badge) VALUES (?, ?, ?, ?)');
    $sth->execute([$name, $description, $glbperm, 'badge_def.png']);
} catch (Exception $e) {
    echo $e;
}

header('location: /admin/list_badge/');
