<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$badge = htmlspecialchars(trim($_GET['badge']));

if (!empty($badge)) {
    $sth = $pdo->prepare('DELETE FROM badge WHERE name = ?');
    $sth->execute([$badge]);
}

header('location: /admin/list_badge/');
exit();
