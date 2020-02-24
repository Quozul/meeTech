<?php include ('../config.php') ?>

<!DOCTYPE html>
<html>
<?php include('../includes/head.php'); ?>
	<body>
		<?php include('../includes/header.php'); ?>
		
		<?php
			if(isset($_GET['msg'])){
				echo '<h2>' . $_GET['msg'] . '</h2>';
			}
		?>
		<h1>Inscription</h1>
		<form action="/profil/sign_up_process.php" method="post">
			<input type="text" name="pseudo" placeholder="Votre pseudo"><br>
			<input type="email" name="email" placeholder="Votre email"><br>
			<input type="password" name="password" placeholder="Votre mot de passe"><br>
			<button type="submit" name="submit">S'inscrire</button>;
		</form>
	</body>
</html>