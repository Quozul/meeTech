<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$pseudo = htmlspecialchars($_POST['username']);
$email = $_POST['email'];
$password = hash('sha256', $_POST['password']);

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

$error = '';

// Pseudo deja existant et longueur comprise 5 et 35 caractères
if (!isset($_POST['username']))
	$error = $error . 'username_not_set;';

if (strlen($_POST['username']) > 35)
	$error = $error . 'username_too_long;';

// existe ou non dans la BBD
$sth = $pdo->prepare('SELECT * FROM users WHERE username = ?');
$sth->execute([$pseudo]);
$rec = $sth->fetch();

if (!empty($rec) && count($rec) > 0)
	$error = $error . 'username_already_taken;';

// Email au format valide et si deja existant
if (!isset($_POST['email']))
	$error = $error . 'email_not_set;';
else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
	$error = $error . 'invalid_email_address;';

// Existe ou non dans la BBD
$sth = $pdo->prepare('SELECT * FROM users WHERE email = ?');
$sth->execute([$email]);
$rec = $sth->fetch();

if (!empty($rec) && count($rec) > 0)
	$error = $error . 'email_already_taken;';

// Password min 8 char
if (!isset($_POST['password']))
	$error = $error . 'password_not_set;';

if (!isset($_POST['confirm-password']) || empty($_POST['confirm-password']))
	$error = $error . 'confirm_password;';
else if ($_POST['password'] != $_POST['confirm-password'])
	$error = $error . 'incorrect_password;';

if (strlen($_POST['password']) < 8)
	$error = $error . 'password_too_short;';

if (!empty($error)) {
	echo 'ERROR\n' . $error;
	exit();
}

echo $pseudo . '<br>';
echo $email . '<br>';
echo $password . '<br>';

// Requete preparée
try {
	$sth = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
	$sth->execute([$pseudo, $email, $password]);
} catch (Exception $e) {
	echo $e;
}

// save user's id in session
$sth = $pdo->prepare('SELECT id_u FROM users WHERE username=? AND password=?');
$sth->execute([$_POST['username'], hash('sha256', $_POST['password'])]);
$_SESSION['userid'] = $sth->fetch()[0];
