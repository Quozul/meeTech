<?php

require('../../config.php');

$id = htmlspecialchars($_GET['id']);
$badge = htmlspecialchars($_GET['badge']);

$sth = $pdo->prepare('DELETE FROM badged WHERE user=? AND badge=?');
$req = $sth->execute([$id, $badge]);

echo $req;
return;
