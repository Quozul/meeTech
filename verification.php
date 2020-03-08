<!DOCTYPE html>
<html>
<?php include('/include/head.php'); ?>

<body>
    <?php include('/include/header.php');
    $sth = $pdo->prepare('UPDATE users SET verified = TRUE WHERE token =?');
    $sth->execute([$_GET['token']]);
    ?>
    <main>
        <p>Votre compte a été validé avec succés</p>
    </main>
    <?php include('/include/footer.php'); ?>
</body>

</html>