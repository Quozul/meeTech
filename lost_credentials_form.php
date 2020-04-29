<!DOCTYPE html>
<html>

<?php include('includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">

    <?php include('includes/header.php'); ?>

    <main role="main" class="container">
        <form id="lost_credentials_form" method="post" action="/actions/profile/rec_pwd.php" autocomplete="off" novalidate>
            <div class="form-group">
                <label for="eamil_rec">Adresse email</label>
                <input type="email" class="form-control" id="email_rec" name="email" placeholder="Votre mail de récupération">
            </div>
            <input type="submit" class="btn btn-primary" value="Envoi du mail">
        </form>
        <hr>
        <form id="token_verification" method="post" action="/actions/profile/verif_code.php" autocomplete="off" novalidate>
            <div class="form-group">
                <label for="verif_code">Adresse email</label>
                <input type="text" class="form-control" id="verif_mail" name="mail" placeholder="code de verification" value="<?= isset($_GET['email'] ? htmlspecialchars($_GET['email']) : '' ; ?>">
            </div>
            <div class="form-group">
                <label for="verif_code">Code de verification</label>
                <input type="text" class="form-control" id="verif_code" name="code" placeholder="code de verification" value="<?= isset($_GET['code'] ? htmlspecialchars($_GET['code']) : '' ; ?>">
            </div>
            <input type="submit" class="btn btn-primary" value="Valider votre code">
        </form>
        <hr>
        <?php

        if (isset($_POST['code'])) {
            $code = ($_POST['code']);
            $sth = $pdo->prepare('SELECT code FROM users WHERE email = ?');
            $sth->execute([$code]);
            $rec = $sth->fetch();



            if ($code != $rec)
                echo "code invalide ou non renseigné";
            else { ?>
                <form id="lost_credentials_form" method="post" action="/actions/profile/new_pwd.php" autocomplete="off" novalidate>
                    <div class="form-group">
                        <label for="password1">Password</label>
                        <input type="password" class="form-control" id="new_pwd" name="new_pwd" placeholder="Votre nouveau mot de passe">
                    </div>
                    <div class="form-group">
                        <label for="password2">Password</label>
                        <input type="password" class="form-control" id="confirm_pwd" name="confirm_pwd" placeholder="Veuillez confirmer votre nouveau mot de passe">
                    </div>
                    <input type="submit" class="btn btn-primary" value="Valider le nouveau mot de passe">
                </form>
        <?php }
        } ?>


    </main>
    <?php include('includes/footer.php'); ?>


</body>


</html>
