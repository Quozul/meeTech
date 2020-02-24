<?php include ('../config.php') ?>
<?php
	var_dump($_POST);

	// Pseudo deja existant et longueur comprise 5 et 35 caractères
	if(!isset($_POST['username']) || strlen($_POST['username']) < 5 || strlen($_POST['username']) > 35){
		// Redirection
		//header('location: sign_up.php?msg=Pseudo invalide');
		exit;
	}
	// Email au format valide et si deja existant
	if(!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		// Redirection
		header('location: sign_up.php?msg=Email invalide');
		exit;
	}
	// Password 8 à 15 char
	if(!isset($_POST['password']) || strlen($_POST['password']) < 8 || strlen($_POST['password']) > 15){
		// Redirection
		header('location: sign_up.php?msg=Mot de passe invalide');
		exit;
	}
	$pseudo = htmlspecialchars($_POST['username']);
	$email = $_POST['email'];
    $password = hash('sha256', $_POST['password']);
    
	// Requete preparée
	try{
		
	
	$q = 'INSERT INTO users (username,email,password) VALUES (:val1, :val2, :val3)'; 
	$req = $pdo->prepare($q);
	$req->execute([
		"val1" => $pseudo,
		"val2" => $email,
		"val3" => $password]
	);
}
catch(Exception $e){echo $e;}

?>