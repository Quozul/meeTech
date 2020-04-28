<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

// Get components
$req = $pdo->prepare('SELECT id_c, brand, name, type, validated FROM component');
$req->execute();
$components = $req->fetchAll();

if ($components) { ?>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Marque</th>
                <th>Nom</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($components as $key => $component) {
                // Get type
                $req = $pdo->prepare('SELECT name FROM component_type WHERE id_t = ?');
                $req->execute([$component['type']]);
                $type = $req->fetch()[0]; ?>
                <tr id="component-row-<?php echo $type['id_c']; ?>">

                    <td><?php echo $component['id_c']; ?></td>
                    <td><?php echo $component['brand']; ?></td>
                    <td><?php echo $component['name']; ?></td>
                    <td><?php echo $type; ?></td>
                    <td>
                        <a href="/view_component.php?id=<?php echo $component['id_c']; ?>" class="btn btn-sm btn-outline-primary">Voir le composant</a>
                        <form action="/admin/hardware/actions/remove_component.php" method="post" class="d-inline">
                            <button type="submit" class="btn btn-sm btn-outline-danger" name="id" value="<?php echo $component['id_c']; ?>">Supprimer</button>
                        </form>
                        <form action="/admin/hardware/actions/validate_component.php" method="post" class="d-inline">
                            <button type="submit" class="btn btn-sm btn-outline-success" name="id" value="<?php echo $component['id_c']; ?>" <?php echo $component['validated'] == 0 ? '' : 'disabled'; ?>><?php echo $component['validated'] == 0 ? 'Valider' : 'Validé'; ?></button>
                        </form>
                    </td>

                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } else { ?>
    <p>Oops, il semblerait qu'il n'y ai rien ici, commencez par ajouter un premier type avec le bouton ci-dessus.</p>
<?php } ?>
