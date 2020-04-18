<?php

include('../../config.php');

$accept = [
    'image/jpeg',
    'image/jpg',
    'image/gif',
    'image/png',
    'image/jfif'
];

// verify the file type
if (!in_array($_FILES['avatar']['type'], $accept)) {
    echo 'Type de fichier non accepté.';
    exit();
}

// limit image size to 1 MB
$maxsize = 1024 * 1024;
if ($_FILES['avatar']['size'] > $maxsize) {
    echo 'Fichier trop lourd (limite de 1Mo).';
    exit();
}

$path = $_SERVER['DOCUMENT_ROOT'] . '/uploads';
if (!file_exists($path)) {
    mkdir($path, 0777, true);
}

// delete previous avatar
$sth = $pdo->prepare('SELECT avatar FROM users WHERE id_u = ?');
$sth->execute([$_SESSION['userid']]);
$avatar = $sth->fetch();

if ($avatar && !empty($avatar[0])) {
    $filepath = $path . '/' . $avatar[0];
    unlink($filepath);
}

// save and update new avatar
$d = new DateTime('now');
$filename = $_SESSION['userid'] . '_' . $d->format('Ymd_Hiu') . '_' . $_FILES['avatar']['name'];
$filepath = $path . '/' . $filename;
move_uploaded_file($_FILES['avatar']['tmp_name'], $filepath);

$sth = $pdo->prepare('UPDATE users SET avatar=? WHERE id_u=?');
$sth->execute([$filename, $_SESSION['userid']]);

echo 'Avatar mis à jour, vous pouvez retourner en arrière !';
?>

<script>
    history.back()
</script>