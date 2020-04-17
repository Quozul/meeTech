<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

// Get components
$req = $pdo->prepare('SELECT id_c, author, component, content FROM component_comment');
$req->execute();
$comments = $req->fetchAll();

if ($comments) { ?>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Auteur</th>
                <th>Composant</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($comments as $key => $comment) { ?>
                <tr id="component-row-<?php echo $type['id_c']; ?>">

                    <td><?php echo $comment['id_c']; ?></td>
                    <td><?php echo $comment['author']; ?></td>
                    <td><?php echo $comment['component']; ?></td>
                    <td>
                        <a href="/view_component.php?id=<?php echo $comment['component'] . '#' . $comment['id_c']; ?>" class="btn btn-sm btn-outline-primary">Voir le commentaire</a>
                        <form action="/admin/hardware/actions/remove_comment.php" method="post" class="d-inline">
                            <button type="submit" class="btn btn-sm btn-outline-danger" name="id" value="<?php echo $comment['id_c']; ?>">Supprimer</button>
                        </form>
                    </td>

                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } else { ?>
    <p>Oops, il semblerait qu'il n'y ai rien ici, commencez par ajouter un premier type avec le bouton ci-dessus.</p>
<?php } ?>