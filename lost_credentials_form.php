<!DOCTYPE html>
<html>

<?php include('includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">

    <?php include('includes/header.php'); ?>

    <main role="main" class="container">
        <form id="lost_credentials_form" method="post" action="/actions/profile/rec_pwd.php" autocomplete="off" novalidate>
            <div class="form-group">
                <label for="exampleInputEmail1">Adresse email</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <input type="submit" class="btn btn-primary" value="Envoi du mail">
        </form>
        <hr>
        <form>
            <div class="form-group">
                <label for="exempleInputCode1">Code de verification</label>
                <input type="text" class="form-control" id="exempleInputCode1">
            </div>
            <input type="submit" class="btn btn-primary" value="Valider votre code">
        </form>
        <hr>
        <form>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword2">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword2">
            </div>
            <input type="submit" class="btn btn-primary" value="Valider le nouveau mot de passe">
        </form>

    </main>
    <?php include('includes/footer.php'); ?>


</body>


</html>