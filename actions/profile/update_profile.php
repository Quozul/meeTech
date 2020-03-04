<?php
include('../../config.php');

/*$accept = [
	'image/jpeg',
	'image/jpg',
	'image/gif',
	'image/png'
];
var_dump($_FILES['image']['type']);
exit();
//type image
if (!in_array($_FILES['image']['type'], $accept)) {
	//header('location: sign_up_form.php?msg=Le fichier n\'pas une image');
	exit();
};
//poids image
$maxsize = 1024 * 1024; //limite 1Mo
if ($_FILES['image']['size'] > $maxsize) {
	//header('location: sign_up_form.php?msg=L\'image est trop volumineuse');
	exit();
}
$path = 'upload';
if (!file_exists($path)) {
	mkdir($path, 0777, true);
}
$filename = $_FILES['image']['name'];
$chemin_image = $path . '/' . $filename;
move_uploaded_file($_FILES['image']['temp_name'], $chemin_image);*/

// delete empty values
foreach ($_POST as $key => $value)
    if (!empty($value)) {

        $sth = $pdo->prepare('UPDATE users SET ' . $key . '=? WHERE id_u=?');

        $sth->execute([$_POST[$key], $_SESSION['userid']]);
    }
