<?php
	include ('../config.php');

	$pseudo = htmlspecialchars($_POST['username']);
	$email = $_POST['email'];
	$password = hash('sha256', $_POST['password']);

	// Pseudo deja existant et longueur comprise 5 et 35 caractères
	if(!isset($_POST['username']) || strlen($_POST['username']) < 5 || strlen($_POST['username']) > 35){
		// Redirection
		header('location: sign_up.php?msg=Pseudo invalide');
		exit();
	}
	// existe ou non dans la BBD
	$sth = $pdo->prepare('SELECT * FROM users WHERE username = ?');
	$sth->execute([$pseudo]);
	$rec = $sth->fetch();
	if(count($rec) > 0){
		?>
		<script>history.back()</script>
		<?php
		exit();
	}
	// Email au format valide et si deja existant
	if(!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		// Redirection
		header('location: sign_up.php?msg=Email invalide');
		exit();
	}
	// Exisste ou non dans la BBD
	$sth = $pdo->prepare('SELECT * FROM users WHERE email = ?');
	$sth->execute([$email]);
	$rec = $sth->fetch();
	if(count($rec) > 0){
		?>
		<script>history.back()</script>
		<?php
		exit();
	}
	// Password 8 à 15 char
	if(!isset($_POST['password']) || strlen($_POST['password']) < 8 ){
		// Redirection
		header('location: sign_up.php?msg=Mot de passe invalide');
		exit();
	}

	echo $pseudo . '<br>';
	echo $email . '<br>';
	echo $password . '<br>';
    
	// Requete preparée
	try{
		$sth = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
		$sth->execute([$pseudo, $email, $password]);
	} catch(Exception $e) {
		echo $e;
	}
?>

<script>history.back()</script>