<?php

include('../../config.php');

$accept = [
    'image/jpeg',
    'image/jpg',
    'image/gif',
    'image/png'
];

//type image
if (!in_array($_FILES['avatar']['type'], $accept)) {
    exit();
}

//poids image
$maxsize = 1024 * 1024; // limite 1Mo
if ($_FILES['avatar']['size'] > $maxsize) {
    exit();
}

$path = $_SERVER['DOCUMENT_ROOT'] . '/uploads';
if (!file_exists($path)) {
    mkdir($path, 0777, true);
}

$filename = $_SESSION['userid'] . '_' . date('Ymd') . '_' . $_FILES['avatar']['name'];
$chemin_image = $path . '/' . $filename;
move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin_image);

$sth = $pdo->prepare('UPDATE users SET avatar=? WHERE id_u=?');
$sth->execute([$filename, $_SESSION['userid']]);

echo 'Avatar mis à jour, vous pouvez retourner en arrière !';
