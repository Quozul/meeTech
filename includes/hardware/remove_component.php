<?php
include('../../config.php');

var_dump($_POST);
$sth = $pdo->prepare('DELETE FROM component WHERE id = ?;');
$sth->execute([$_POST['id']]);
echo 'Done!';
