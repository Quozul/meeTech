<!DOCTYPE html>
<html>
<?php
$page_name = 'Profil';
include('includes/head.php');
?>

<body class="d-flex vh-100 flex-column justify-content-between">
    <?php include('includes/header.php'); ?>
    <main role="main" class="container">
        <h1>Votre profil</h1>
        <div class="jumbotron">
            <?php
                $sth = $pdo->prepare('SELECT avatar, username, email, location, prefered_language, bio FROM users WHERE id_u=?');
                $sth->execute([$_GET['userid']]);
                $result = $sth->fetch(); ?>

                <!-- TODO: Action file -->

                <form id="avatar-form" <?php if (!isset($_SESSION['userid']) || $_GET['userid'] != $_SESSION['userid']) { ?>action="/actions/profile/update_avatar.php" <?php } ?> method="post" autocomplete="off" enctype="multipart/form-data">
                    <div class="form-group float-right col-10">
                        <label for="avatar">Avatar</label>
                        <input type="file" class="form-control-file" name="avatar" id="avatar" <?php if (!isset($_SESSION['userid']) || $_GET['userid'] != $_SESSION['userid']) echo 'readonly'; ?>>
                    </div>
                    <div class="mt-avatar col-2" style="width: 64px; height: 64px; background-image: url('/uploads/<?php echo $result['avatar']; ?>');"></div>
                    <button type="submit" class="btn btn-primary mt-2">Envoyer l'avatar</button>
                </form>

                <hr>

                <form method="post" action="/actions/profile/update_profile.php" id="profile">
                    <div class="form-group">
                        <label for="username">Nom d'utilisateur</label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="Pseudonyme" value="<?php echo $result['username']; ?>" <?php if (!isset($_SESSION['userid']) || $_GET['userid'] != $_SESSION['userid']) echo 'readonly'; ?>>
                    </div>
                    <div class=" form-group">
                        <label for="email">Adresse e-mail</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Adresse e-mail" value="<?php echo $result['email']; ?>" <?php if (!isset($_SESSION['userid']) || $_GET['userid'] != $_SESSION['userid']) echo 'readonly'; ?>>
                    </div>
                    <div class=" form-group">
                        <label for="pays">Pays</label>
                        <input type="text" class="form-control" name="location" id="pays" placeholder="Pays" value="<?php echo $result['location']; ?>" <?php if (!isset($_SESSION['userid']) || $_GET['userid'] != $_SESSION['userid']) echo 'readonly'; ?>>
                    </div>
                    <div class=" form-group">
                        <label for="langue">Langue préférée</label>
                        <input type="text" class="form-control" name="prefered_language" id="langue" placeholder="Langue" value="<?php echo $result['prefered_language']; ?>" <?php if (!isset($_SESSION['userid']) || $_GET['userid'] != $_SESSION['userid']) echo 'readonly'; ?>>
                    </div>
                    <div class=" form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="bio" id="description" rows="3"<?php echo $result['bio']; ?><?php if (!isset($_SESSION['userid']) || $_GET['userid'] != $_SESSION['userid']) echo 'readonly'; ?>></textarea>
                    </div>
                    <?php if (!isset($_SESSION['userid']) || $_GET['userid'] != $_SESSION['userid']) { ?><button type="button" class="btn btn-primary" onclick="update_profile()">Sauvegarder les modifications</button><?php } ?>

                    <div id="clear_session" class="modal fade" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Suppression du compte</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Êtes-vous sûrs de vouloir supprimer votre compte ?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Annuler</button>
                                    <button type="button" class="btn btn-danger" onclick="clear_session()">Supprimer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#clear_session">
                        Supprimer votre compte
                    </button>
                </form>
            <script>
                function update_profile() {
                    request('/actions/profile/update_profile.php', formToQuery('profile')).then(function(req) {
                        document.location.reload();
                    }).catch(function(req) {
                        alert('Une erreur est survenue, contacter un administrateur avec le code d\'erreur suivant :\n' + req.status);
                    });
                }

                function clear_session() {
                    request('/actions/profile/clear_session.php', '').then(function(req) {
                        console.log(req.response);

                        document.location.reload();
                    }).catch(function(req) {
                        alert('Une erreur est survenue, contacter un administrateur avec le code d\'erreur suivant :\n' + req.status);
                    });
                }
            </script>
        </div>
    </main>

    <?php include('includes/footer.php'); ?>

</body>

</html>
