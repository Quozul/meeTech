<!DOCTYPE html>
<html>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'); ?>

<body>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>
    <main>
        <?php $sth = $pdo->prepare('SELECT id_u,username, email, avatar, prefered_language, location, bio FROM users');
        $sth->execute([]);
        $rec = $sth->fetchAll(); ?>

        <h1>Liste des noms d'utilisateurs</h1>
        <ul>

            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">id</th>
                        <th scope="col">avatar</th>
                        <th scope="col">Pseudo</th>
                        <th scope="col">email</th>
                        <th scope="col">Pays</th>
                        <th scope="col">Langue</th>
                        <th scope="col">Bio</th>
                        <th scope="col">Supression</th>
                    </tr>
                </thead>
                <?php foreach ($rec as $key => $user) { ?>
                    <tbody>
                        <tr>
                            <th scope="row"><?php echo $user['id_u']; ?></th>
                            <td><?php echo $user['avatar']; ?></td>
                            <td><a href="https://www.meetech.ovh/user/?id=<?php echo $user['id_u']; ?>"><?php echo $user['username']; ?></a></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['location']; ?></td>
                            <td><?php echo $user['prefered_language']; ?></td>
                            <td><?php echo $user['bio']; ?></td>
                            <td>
                                <div id="drop_account<?php echo $user['id_u']; ?>" class="modal fade" tabindex="-1" role="dialog">
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
                                                <button onclick="drop_account(<?php echo $user['id_u']; ?>)" class="btn btn-danger">Supprimer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#drop_account<?php echo $user['id_u']; ?>">
                                    Suppression du compte
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
            </table>
        </ul>
    </main>
    <script>
        function drop_account(userid) {
            request('/admin/actions/drop_account.php?user=' + userid, '').then(function(req) {
                console.log(req.response);

                document.location.reload();
            }).catch(function(req) {
                alert('Une erreur est survenue, contacter un administrateur avec le code d\'erreur suivant :\n' + req.status);
            });
        }
    </script>
</body>

</html>
