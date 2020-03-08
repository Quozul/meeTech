<!DOCTYPE html>
<html>
<?php include('includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">
    <?php include('includes/header.php');
    $sth = $pdo->prepare('UPDATE users SET verified = TRUE WHERE token =?');
    $sth->execute([$_GET['token']]);
    ?>
    <main role="main" class="container">
        <div class="jumbotron">
            <p class="text-justify">
                Votre compte a été validé avec succés cliquez ici pour revenir à l'acceuil de<a href="/">meetech</a>
            </p>
        </div>

    </main>
    <?php include('includes/footer.php'); ?>
</body>

</html>