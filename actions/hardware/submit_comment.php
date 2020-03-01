<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

if (empty($_POST['comment']))
    exit();

$sth = $pdo->prepare('INSERT INTO component_comment (author, content, component, date_published) VALUES (?, ?, ?, now())');
$sth->execute([$_SESSION['userid'], htmlspecialchars($_POST['comment']), $_POST['id']]);
