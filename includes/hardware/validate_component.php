<?php
include('../../config.php');

var_dump($_POST);
$sth = $pdo->prepare('UPDATE component SET validated = 1 WHERE id = ?;');
$sth->execute([$_POST['id']]);
echo 'Done!';
