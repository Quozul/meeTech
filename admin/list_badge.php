<!DOCTYPE html>
<html lang="fr">
<?php
$page_name = 'List_badge';
include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php');
?>

<body class="d-flex vh-100 flex-column justify-content-between">
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>
    <main class="container">
        <div class="jumbotron">
            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#submitModal">Ajouter un badge</button>
            <div class="modal fade bd-example-modal-xl" id="submitModal" tabindex="-1" role="dialog" aria-labelledby="submitModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="submitModalTitle">Nouveau Badge</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="submit-badge" method="post" action="/admin/actions/badges/add_badge/" autocomplete="off">
                                <input type="text" class="form-control mb-3" id="name" name="name" placeholder="Nouveau badge">
                                <input type="text" class="form-control" id="description" name="description" placeholder="Ex : Décerné pour avoir ... n fois">
                                <input type="number" class="form-control mb-3" id="global_perm" name="global_perm" placeholder="Permission globale">
                                <input type="number" class="form-control mb-3" id="obtention" name="obtention" placeholder="Obtention">
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary" form="submit-badge">Ajouter</button>
                        </div>
                    </div>
                </div>
            </div>
            <h2>Badges</h2>
            <hr>
            <?php
            $message = '';
            if (isset($_GET['error'])) {
                if (htmlspecialchars($_GET['error']) == 'setlang') $message = 'Saisissez un nom de badge';
                else if (htmlspecialchars($_GET['error']) == 'elsewhere') $message = 'Ce badge existe déjà';
                if (!empty($message)) {
            ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $message; ?>
                    </div>
                <?php
                }
            }

            $message = '';
            if (isset($_GET['success'])) {
                if (htmlspecialchars($_GET['success']) == 'edit') $message = 'Badge éditée';
                else if (htmlspecialchars($_GET['success']) == 'add') $message = 'Badge ajoutée';
                else if (htmlspecialchars($_GET['success']) == 'drop') $message = 'Badge supprimée';
                if (!empty($message)) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?= $message; ?>
                    </div>
            <?php
                }
            }

            $q = $pdo->prepare('SELECT name, description, global_permissions, img_badge, obtention FROM badge');
            $q->execute();
            $badge = $q->fetchAll();
            ?>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Image</th>
                        <th scope="col">Nom du badge</th>
                        <th scope="col">Description</th>
                        <th scope="col">Permissions</th>
                        <th scope="col">Obtention</th>
                        <th scope="col">Modifier</th>
                        <th scope="col">Supprimer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($badge as $value) {
                    ?>
                        <tr>
                            <form action="/admin/actions/badges/update_badge.php" method="post">
                                <td class="row">
                                    <img src="/images/<?= $value['img_badge']; ?>" alt="<?= $value['img_badge']; ?>" style="max-height: 64px; max-width: 64px;" title="<?= $value['description']; ?>">
                                </td>
                                <td>
                                    <input type="text" name="name" value="<?= $value['name']; ?>" class="form-control" readonly>
                                </td>
                                <td>
                                    <textarea type="text" name="description" class="form-control col-md-12 col-form-label-sm" rows="2"><?= $value['description']; ?></textarea></td>
                                </td>
                                <td>
                                    <input type="number" name="global_permissions" value="<?= $value['global_permissions']; ?>" class="form-control col-md-5"></td>
                                </td>
                                <td>
                                    <input type="number" name="obtention" value="<?= $value['obtention']; ?>" class="form-control col-md-5"></td>
                                </td>
                                <td>
                                    <input type="submit" value="Valider les modifications" class="btn btn-outline-success btn-sm">
                                </td>
                            </form>
                            <td>
                                <a href="/admin/actions/badges/drop_badge.php?badge=<?= $value['name']; ?>" class="btn btn-outline-danger btn-sm">Supprimer</a>
                            </td>
                        </tr>
                    <?php
                    };
                    ?>
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>
