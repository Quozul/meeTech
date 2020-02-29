<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

echo $_POST['comment'];

$sth = $pdo->prepare('INSERT INTO component_comment (author, content, component, date_published) VALUES (?, ?, ?, now())');
$sth->execute([$_SESSION['userid'], $_POST['comment'], $_POST['id']]);
