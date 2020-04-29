<!DOCTYPE html>
<html lang="fr">
<?php include('includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">
    <?php include('includes/header.php'); ?>
    <main class="container">
        <h1>Vérification</h1>
        <div class="jumbotron">
            <p class="text-justify">
                <?php
                if (isset($_GET['token'])) {
                    $sth = $pdo->prepare('UPDATE users SET verified = TRUE WHERE token =?');
                    $token = htmlspecialchars(trim($_GET['token']));
                    $sth->execute([$token]);

                    $query = $pdo->prepare('SELECT id_u, verified FROM users WHERE token =?');
                    $result = $query->execute([$token]);
                    $res = $query->fetch();

                    if (!$res['verified']) {
                        echo "Le compte n'est pas validé";
                    } else {
                        $_SESSION['userid'] = $res[0];
                        $sth = $pdo->prepare('UPDATE users SET token = NULL WHERE id_u = ?');
                        $query->execute([$_SESSION['userid']]);
                        $res = $sth->fetch();
                ?>
                        <p>Votre compte a été validé avec succés cliquez <a href="/">ici</a> pour revenir à l'acceuil de meetech</p>
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