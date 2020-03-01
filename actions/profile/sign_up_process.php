<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$pseudo = htmlspecialchars($_POST['username']);
$email = $_POST['email'];
$password = hash('sha256', $_POST['password']);

// Pseudo deja existant et longueur comprise 5 et 35 caractères
if (!isset($_POST['username']) || strlen($_POST['username']) > 35) {
	exit();
}
// existe ou non dans la BBD
$sth = $pdo->prepare('SELECT * FROM users WHERE username = ?');
$sth->execute([$pseudo]);
$rec = $sth->fetch();
if (!empty($rec) && count($rec) > 0) {
	exit();
}
// Email au format valide et si deja existant
if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	exit();
}
// Exisste ou non dans la BBD
$sth = $pdo->prepare('SELECT * FROM users WHERE email = ?');
$sth->execute([$email]);
$rec = $sth->fetch();
if (!empty($rec) && count($rec) > 0) {
	exit();
}
// Password 8 à 15 char
if (!isset($_POST['password']) || strlen($_POST['password']) < 8) {
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
