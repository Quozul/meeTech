<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

// Get types
$req = $pdo->prepare('SELECT id_t, name, category FROM component_type');
$req->execute();
$types = $req->fetchAll();
if ($types) { ?>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Categorie</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($types as $key => $type) { ?>
                <tr id="component-type-row-<?php echo $type['id_t']; ?>">

                    <td><?php echo $type['id_t']; ?></td>
                    <td><?php echo $type['name']; ?></td>
                    <td><?php echo $type['category']; ?></td>
                    <td>
                        <a href="/admin/hardware/edit_type.php?id=<?php echo $type['id_t']; ?>" class="btn btn-sm btn-outline-primary">Voir le type</a>
                        <!-- <a href="#" class="btn btn-sm btn-outline-danger" onclick="remove_type(<?php echo $type['id_t']; ?>);">Supprimer</a> -->
                        <form action="/admin/hardware/actions/remove_type.php" method="post" class="d-inline">
                            <button type="submit" class="btn btn-sm btn-outline-danger" name="id" value="<?php echo $type['id_t']; ?>">Supprimer</button>
                        </form>
                    </td>

                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } else { ?>
    <p>Oops, il semblerait qu'il n'y ai rien ici, commencez par ajouter un premier type avec le bouton ci-dessus.</p>
<?php } ?>