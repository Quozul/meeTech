<!DOCTYPE html>
<html>
<?php
$page_name = 'Profil';
include('../includes/head.php');
?>

<body class="d-flex vh-100 flex-column justify-content-between">
    <?php include('../includes/header.php'); ?>
    <main role="main" class="container">
        <h1>Votre profil</h1>
        <div class="jumbotron">
            <?php if (!empty($_SESSION['userid'])) {
                $sth = $pdo->prepare('SELECT * FROM users WHERE id_u=?');

                $sth->execute([$_SESSION['userid']]);

                $result = $sth->fetch(); ?>

                <form method="post" action="/profil/update_profil.php" id='profil'>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="Mon Pseudo" value=<?php echo $result['username']; ?>>
                    </div>
                    <div class="form-group">
                        <label for="email">Adresse E-mail</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="exemple@exemple.com" value=<?php echo $result['email']; ?>>
                    </div>
                    <div class="form-group">
                        <label for="pays">Pays</label>
                        <input type="text" class="form-control" name="location" id="pays" placeholder="Pays" value=<?php echo $result['location']; ?>>
                    </div>
                    <div class="form-group">
                        <label for="langue">Langue préféré</label>
                        <input type="text" class="form-control" name="prefered_language" id="langue" placeholder="Langue" value=<?php echo $result['prefered_language']; ?>>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="bio" id="description" rows="3"><?php echo $result['bio']; ?></textarea>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="update_profil()">Sauvegarder les modifications</button>
                </form>
            <?php } else { ?>
                <div class="alert alert-danger" role="alert">
                    Il faut être connecter pour pouvoire modifier votre profil !
                </div>
            <?php  } ?>
            <script>
                function update_profil() {
                    request('/profil/update_profil.php', formToQuery('profil')).then(function(req) {
                        document.location.reload();
                    }).catch(function(req) {
                        alert('Une erreur est survenue, contacter un administrateur avec le code d\'erreur suivant :\n' + req.status);
                    });
                }
            </script>
        </div>
    </main>

    <?php include('../includes/footer.php'); ?>

</body>

</html>