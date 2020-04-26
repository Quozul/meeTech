<?php
require('../../config.php');
$user = $_SESSION['userid'];
$channel = htmlspecialchars($_GET['chan']);

$sth = $pdo->prepare('DELETE FROM recipient WHERE author = ? AND channel = ?');
$res = $sth->execute([$user, $channel]);

if ($res == 1) {
    $stmt = $pdo->prepare('SELECT COUNT(author) FROM recipient WHERE channel = ?');
    $stmt->execute([$channel]);
    $count = $stmt->fetch()[0];
    if ($count == 0) {
        $query = $pdo->prepare('DELETE FROM channel WHERE id_c = ?');
        $res2 = $query->execute([$channel]);
    }
}

echo isset($res2) ? $res2 && $res : $res;
return;
