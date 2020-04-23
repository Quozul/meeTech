<!DOCTYPE html>
<html>
<?php include('includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">
    <?php include('includes/header.php'); ?>
    <main role="main" class="container">
        <h1>Vérification</h1>
        <div class="jumbotron">
            <p class="text-justify">
                <?php
                if (isset($_GET['token'])) {
                    $sth = $pdo->prepare('UPDATE users SET verified = TRUE WHERE token =?');
                    $token = htmlspecialchars(trim($_GET['token']));
                    $sth->execute([$token]);

                    $query = $pdo->prepare('SELECT id_u, verified FROM users WHERE token =?');
                    $query->execute([$token]);
                    $res = $sth->fetch();

                    if (!$res['verified'])
                        echo "Le compte n'est pas validé";
                    else {
                        $_SESSION['userid'] = $res[0];
                ?>
                        Votre compte a été validé avec succés cliquez <a href="/">ici</a> pour revenir à l'acceuil de meetech
                <?php
                    }
                } else {
                    echo "Le token n'est pas défini !";
                } ?>
            </p>
        </div>
    </main>
    <?php include('includes/footer.php'); ?>
</body>

</html>