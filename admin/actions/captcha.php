<?php

include('../../config.php');

// verify the file type
if ($_FILES['captcha']['type'] != 'image/png') {
    echo 'Type de fichier non accepté.';
    exit();
}

// limit image size to 1 MB
$maxsize = 1024 * 1024;
if ($_FILES['captcha']['size'] > $maxsize) {
    echo 'Fichier trop lourd (limite de 1Mo).';
    exit();
}

$path = $_SERVER['DOCUMENT_ROOT'] . '/assets';
if (!file_exists($path))
    mkdir($path, 0777, true);

// save and update new avatar
$filename = 'captcha.png';
$filepath = $path . '/' . $filename;
move_uploaded_file($_FILES['captcha']['tmp_name'], $filepath);

echo 'Avatar mis à jour, vous pouvez retourner en arrière !';

?>

<script>
    history.back()
</script>