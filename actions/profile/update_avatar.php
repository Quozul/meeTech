<?php

include('../config.php');

$sth = $pdo->prepare('INSERT INTO users(avatar) VALUES (?)');
$sth->excute([$chemin_image]);

$accept = [
    'image/jpeg',
    'image/jpg',
    'image/gif',
    'image/png'
];
//type image
if (!in_array($_FILES['image']['type'], $accept)) {
    exit();
}
//poids image
$maxsize = 1024 * 1024; //limite 1Mo
if ($_FILES['image']['size'] > $maxsize) {
    exit();
}
$path = 'upload';
if (!file_exists($path)) {
    mkdir($path, 0777, true);
}
$filename = $_FILES['image']['name'];
$chemin_image = $path . '/' . $filename;
move_uploaded_file($_FILES['image']['temp_name'], $chemin_image);
