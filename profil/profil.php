<!DOCTYPE html>
<html>
<?php
$page_name = 'Profil';
include('../includes/head.php');
?>

<body class="d-flex vh-100 flex-column justify-content-between">
    <?php include('../includes/header.php'); ?>
    <main role="main" class="container">
        <form method="get" action="/profil/update_profil.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" placeholder="Mon Pseudo">
            </div>
            <div class="form-group">
                <label for="email">Adresse E-mail</label>
                <input type="email" class="form-control" id="email" placeholder="name@example.com">
            </div>
            <div class="form-group">
                <label for="pays">Pays</label>
                <input type="text" class="form-control" id="pays" placeholder="Pays">
            </div>
            <div class="form-group">
                <label for="langue">Langue préféré</label>
                <input type="text" class="form-control" id="langue" placeholder="Langue">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Sauvegarder les modifications</button>
        </form>
    </main>

    <?php include('../includes/footer.php'); ?>

</body>

</html>