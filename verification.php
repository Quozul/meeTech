<!DOCTYPE html>
<html>
<?php include('includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">
    <?php include('includes/header.php');
    $sth = $pdo->prepare('UPDATE users SET verified = TRUE WHERE token =?');
    $sth->execute([$_GET['token']]);
    ?>
    <main role="main" class="container">
        <h1>Vérification</h1>
        <div class="jumbotron">
            <p class="text-justify">
                Votre compte a été validé avec succés cliquez <a href="/">ici</a> pour revenir à l'acceuil de meetech
            </p>
        </div>

    </main>
    <?php include('includes/footer.php'); ?>
</body>

</html>