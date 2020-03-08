<!DOCTYPE html>
<html>
<?php include('/includes/head.php'); ?>

<body>
    <?php include('/includes/header.php');
    $sth = $pdo->prepare('UPDATE users SET verified = TRUE WHERE token =?');
    $sth->execute([$_GET['token']]);
    ?>
    <main>
        <p>Votre compte a été validé avec succés</p>
    </main>
    <?php include('/includes/footer.php'); ?>
</body>

</html>