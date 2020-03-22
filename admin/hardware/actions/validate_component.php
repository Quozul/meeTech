<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');
$req = $pdo->prepare('UPDATE component SET validated = 1 WHERE id_c = ?');
$req->execute([$_POST['id']]);
