<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');
$req = $pdo->prepare('DELETE FROM component where id_c = ?');
$req->execute([$_POST['id']]);
