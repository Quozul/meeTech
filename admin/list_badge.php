<!DOCTYPE html>
<html>
<?php
$page_name = 'List_badge';
include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php');
?>

<body class="d-flex vh-100 flex-column justify-content-between">
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>
    <main role="main" class="container">
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
                            <form id="submit-badge" method="post" action="/admin/actions/badges/add_badge.php" autocomplete="off">
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
                    <hr>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $message; ?>
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
                    <hr>
                    <div class="alert alert-success" role="alert">
                        <?php echo $message; ?>
                    </div>
            <?php
                }
            }

            $q = $pdo->prepare('SELECT name, description, global_permissions, obtention, img_badge FROM badge');
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
                                <td>
                                    <div class="row">
                                        <img src="/images/<?php echo $value['img_badge']; ?>" alt="<?php echo $value['img_badge']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="icon" value="<?php echo htmlspecialchars(substr($value['name'], 0, 8)) . ' ' . htmlspecialchars(substr($value['name'], 8, 8)); ?>" class="form-control">
                                </td>
                                <td>
                                    <textarea type="text" name="description" class="form-control col-md-12" rows="2"><?php echo $value['description']; ?></textarea></td>
                                </td>
                                <td>
                                    <input type="number" name="global_perm" value="<?php echo $value['global_permissions']; ?>" class="form-control col-md-5 float-right"></td>
                                </td>
                                <td>
                                    <input type="number" name="obtention" value="<?php echo $value['obtention']; ?>" class="form-control col-md-5 float-right"></td>
                                </td>
                                <td>
                                    <input type="submit" value="Valider les modifications" class="btn btn-outline-success btn-sm">
                                </td>
                            </form>
                            <td>
                                <a href="/admin/actions/badges/drop_badge.php?lang=<?php echo $value['name']; ?>" class="btn btn-outline-danger btn-sm">Supprimer</a>
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

<?php
//AJAX => affichage modal contenant les img

//dans le modal => SELECT img_badge FROM badge WHERE file_name = Badge% 
?>
<!-- <div>
    <?php foreach ($result as $image) { ?>
        <img src="/images/<?= $image['file_name']; ?>" alt="<?= $image['file_name']; ?>">
    <?php } ?>
</div> -->