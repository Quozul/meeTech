<!DOCTYPE html>
<html>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'); //config.php dans le head.php
?>

<body class="d-flex vh-100 flex-column justify-content-between">

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

    <main role="main" class="container">

        <?php $sth = $pdo->prepare('SELECT avatar, username, email, location, prefered_language, bio, display_email FROM users WHERE id_u = ?');
        $sth->execute([$_GET['id']]);
        $result = $sth->fetch(); ?>

        <?php if ($result) { ?>
            <h1>Profil de <?php echo $result['username']; ?></h1>
        <?php } else { ?>
            <h1>Utilisateur introuvable</h1>
        <?php } ?>
        <div class="jumbotron">
            <?php if (!$result) {
                echo "<h2>Cet utilisateur n'existe pas.</h2>";
            } else { ?>
                <img src="/uploads/<?= $result['avatar'] ; ?>" alt="Profile picture" class="mt-avatar col-2 rounded float-left mr-3 mb-3" style="max-width:100px; max-height:100px">

                <b>Langue :</b> <?php echo $result['prefered_language']; ?><br>
                <b>Localisation :</b> <?php echo $result['location']; ?><br>
                <?php if ($result['display_email']) { ?>
                    <b>Adresse e-mail :</b> <?php echo $result['email']; ?><br>
                <?php } ?>
                <b>Description :</b> <?php echo $result['bio']; ?><br>
            <?php } ?>
        </div>
    </main>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

</body>

</html>
