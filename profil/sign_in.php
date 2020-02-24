<?php include ('../config.php'); ?>

<!DOCTYPE html>
<html>
<?php include('../includes/header.php') ?>
	<body>
		<?php include('../includes/header.php') ?>
		<?php
			if(isset($_GET['msg'])){
				echo '<h2>' . $_GET['msg'] . '</h2>';
			}
		?>
		<h1>connection</h1>
		<form action="/profil/sign_in_process.php" method="post">
			<input type="text" name="username" placeholder="Votre pseudo"><br>
			<input type="password" name="password" placeholder="Votre mot de passe"><br>
			<input type="submit" name="submit" value="Se connecter">
		</form>
		<?php include('../includes/footer.php') ?>
	</body>
</html>

