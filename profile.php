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
            <?php if (!empty($_SESSION['userid'])) {
                $sth = $pdo->prepare('SELECT avatar, username, email, location, prefered_language, bio FROM users WHERE id_u = ?');
                $sth->execute([$_SESSION['userid']]);
                $result = $sth->fetch(); ?>

                <!-- TODO: Action file -->
                <form id="avatar-form" action="/actions/profile/update_avatar.php" method="post" autocomplete="off" enctype="multipart/form-data">
                    <div class="form-group float-right col-10">
                        <label for="avatar">Avatar</label>
                        <input type="file" class="form-control-file" name="avatar" id="avatar">
                    </div>
                    <img src="/uploads/<?php echo $result['avatar']; ?>" alt="<?= $result['username'] ; ?>'s avatar'" class="mt-avatar col-2" style="max-width: 100px; max-height: 100px;">
                    <button type="submit" class="btn btn-primary mt-2">Envoyer l'avatar</button>
                </form>

                <hr>

                <?php include('includes/countries.php'); ?>
                <form method="post" action="/actions/profile/update_profile.php" id="profile">
                    <div class="form-group">
                        <label for="username">Nom d'utilisateur</label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="Pseudonyme" value="<?php echo $result['username']; ?>">
                    </div>
                    <div class=" form-group">
                        <label for="email">Adresse e-mail</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Adresse e-mail" value="<?php echo $result['email']; ?>">
                    </div>
                    <div class=" form-group">
                        <label for="pays">Pays</label>
                        <select class="custom-select" name="location">
                            <?php displayCountryList($result['location']); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="langue">Langue préférée</label>
                        <select class="form-control" name="prefered_language" id="langue">
                            <option<?php if ($result['prefered_language'] == NULL) echo ' selected'; ?>>Choose…</option>
                                <?php
                                $q = $pdo->query('SELECT lang FROM language');
                                while ($language = $q->fetch()['lang']) {
                                ?>
                                    <option<?php if ($result['prefered_language'] == $language) echo ' selected'; ?>><?php echo $language; ?></option>
                                    <?php
                                }
                                    ?>
                        </select>
                    </div>
                    <div class=" form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="bio" id="description" rows="3"><?php echo $result['bio']; ?></textarea>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="update_profile()">Sauvegarder les modifications</button>

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
            <?php } else { ?>
                <div class="alert alert-danger" role="alert">
                    Vous devez être connecté pour pouvoir modifier votre profil !
                </div>
            <?php  } ?>
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
