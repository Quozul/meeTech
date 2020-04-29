<?php

require('../../config.php');

$id = htmlspecialchars($_GET['id']);
$badge = htmlspecialchars($_GET['badge']);

$query = $pdo->prepare('SELECT COUNT(badge) FROM badged WHERE badge = ? AND user = ?');
$query->execute([$badge, $id]);
$count = $query->fetch()[0];
if ($count > 0) {
    echo '-1';
    return;
}

$sth = $pdo->prepare('INSERT INTO badged (user, badge) VALUES( ?, ?)');
$req = $sth->execute([$id, $badge]);

echo $req;
return;
