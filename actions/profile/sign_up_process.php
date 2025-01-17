<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

// verification stuff and error feedback
$error = '';

$pseudo = htmlspecialchars(trim($_POST['username']));

if (!isset($_POST['puzzle-completed']))
	$error = $error . 'no_captcha;';

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

// Password min 8 char
if (!isset($_POST['password']))
	$error = $error . 'password_not_set;';

if (!isset($_POST['confirm-password']) || empty($_POST['confirm-password']))
	$error = $error . 'confirm_password;';
else if ($_POST['password'] != $_POST['confirm-password'])
	$error = $error . 'incorrect_password;';

if (strlen(trim($_POST['password'])) < 8)
	$error = $error . 'password_too_short;';

// Email au format valide et si deja existant
if (!isset($_POST['email']))
	$error = $error . 'email_not_set;';
else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
	$error = $error . 'invalid_email_address;';
else {
	// Existe ou non dans la BBD
	$email = $_POST['email'];
	$sth = $pdo->prepare('SELECT * FROM users WHERE email = ?');
	$sth->execute([$email]);
	$rec = $sth->fetch();

	if (!empty($rec) && count($rec) > 0)
		$error = $error . 'email_already_taken;';
}

if (!empty($error)) {
	echo 'ERROR\n' . $error;
	exit();
}

$password = hash('sha256', trim($_POST['password']));

$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type:text/html;charset=UTF-8\r\n";
$headers .= "From: no-reply@meetech.ovh\r\n";
$email = $_POST['email'];
$subject = "Validation de votre compte";
$token = uniqid();
$message = "Cliquez sur le lien pour valider votre compte : https://www.meetech.ovh/verification/?token=" . $token;

mail($email, $subject, $message, $headers);

$default_image = 'def_avatar.png';

// Requete preparée
try {
	$sth = $pdo->prepare('INSERT INTO users (username, email, password, token, avatar) VALUES (?, ?, ?, ?, ?)');
	$sth->execute([$pseudo, $email, $password, $token, $default_image]);
} catch (Exception $e) {
	echo $e;
}
