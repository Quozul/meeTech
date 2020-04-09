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
                        <th scope="col">select</th>
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
                            <th>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="checkbox" aria-label="Checkbox for following text input" onchange="addToDelete(<?= $user['id_u']; ?>)">
                                        </div>
                                    </div>
                                </div>
                            </th>
                            <td scope="row" id="user<?= $user['id_u']; ?>"><?= $user['id_u']; ?></td>
                            <td><?= $user['avatar']; ?></td>
                            <td><a href="https://www.meetech.ovh/user/?id=<?= $user['id_u']; ?>"><?= $user['username']; ?></a></td>
                            <td><?= $user['email']; ?></td>
                            <td><?= $user['location']; ?></td>
                            <td><?= $user['prefered_language']; ?></td>
                            <td><?= $user['bio']; ?></td>
                            <td>
                                <div id="drop_account<?= $user['id_u']; ?>" class="modal fade" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Suppression du compte</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Êtes-vous sûrs de vouloir supprimer le compte <?= $user['id_u']; ?>?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">Annuler</button>
                                                <button onclick="drop_account(<?= $user['id_u']; ?>)" class="btn btn-danger">Supprimer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#drop_account<?= $user['id_u']; ?>">
                                    Suppression du compte
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
            </table>
            <button type="button" class="btn btn-danger" data-target="#drop_accounts" data-toggle="modal">Supprimer les comptes</button>
            <div id="drop_accounts" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Suppression de multiples comptes</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Êtes-vous sûrs de vouloir supprimer les comptes sélectionnés ?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Annuler</button>
                            <button onclick="drop_accounts()" class="btn btn-danger">Supprimer</button>
                        </div>
                    </div>
                </div>
            </div>
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

        const toDelete = [];

        function addToDelete(i) {
            const tag = document.getElementById('user' + i);
            const idUser = tag.innerHTML;
            index = -1;
            for (let i = 0; i < toDelete.length; ++i) {
                if (toDelete[i] == idUser) index = i;
            }
            console.log(index);
            if (index == -1) toDelete.push(i);
            else toDelete.splice(index, 1);
            console.log(toDelete);
        }

        function drop_accounts() {
            for (let i = 0; i < toDelete.length; ++i) {
                drop_account(toDelete[i]);
            }
        }
    </script>
</body>

</html>