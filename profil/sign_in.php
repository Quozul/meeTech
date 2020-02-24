<?php include ('../config.php'); ?>

<!DOCTYPE html>
<html>
	<head>
		<title>Connection</title>
		<meta charset="utf-8">
	</head>
	<body>
		<? php include('header.php') ?>
		<?php
			if(isset($_GET['msg'])){
				echo '<h2>' . $_GET['msg'] . '</h2>';
			}
		?>
		<h1>connection</h1>
		<form action="sign_in_process.php" method="post">
			<input type="text" name="username" placeholder="Votre pseudo"><br>
			<input type="password" name="password" placeholder="Votre mot de passe"><br>
			<input type="submit" name="submit" value="Se connecter">
		</form>
	</body>
</html>

