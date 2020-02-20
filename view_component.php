<?php
include('config.php');
$page_limit = 3;
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Columns names and description
$GLOBALS['cols'] = json_decode(file_get_contents('includes/hardware/specifications.json'), true);
?>

<!DOCTYPE html>
<html>
<?php include('includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">
    <?php include('includes/header.php'); ?>

    <main role="main" class="container">
        <!-- Get component's informations -->
        <?php
        $sth = $pdo->prepare('SELECT * FROM component WHERE id = ?');
        $sth->execute([$_GET['id']]);
        $component = $sth->fetch();
        $specs = json_decode($component['specifications'], true);
        ?>

        <h1>
            <a class="btn btn-primary" role="button" href="javascript: history.go(-1)">« Retour</a>
            Composant
        </h1>
        <div class="jumbotron">
            <?php if ($component['validated'] == 0) { ?>
                <form action="/admin/edit_component.php" method="post">
                    <button type="submit" class="btn btn btn-primary float-right" name="id" value="<?php echo $component['id']; ?>">Proposer une modification</button>
                </form>
            <?php } ?>

            <h2>
                <?php
                echo $specs['brand'] . ' ' . $specs['name'];
                ?>
            </h2>
            <p class="text-muted">
                <?php
                $d = new DateTime($component['added_date']);

                $username = 'Anonyme';

                if (isset($component['added_by'])) {
                    $sth = $pdo->prepare('SELECT username FROM users WHERE id_user = ?');
                    $sth->execute([$component['added_by']]); // remplace le ? par la valeur
                    $result = $sth->fetch();

                    if ($result)
                        $username = $result['username'];
                }

                // TODO: Add link to user's profile
                echo 'Ajouté par ' . $username . ' le ' . $d->format('d M yy');

                if ($component['validated'] == 0) {
                    echo '<span class="badge badge-danger badge-pill float-right" data-toggle="tooltip" data-placement="top" title="Les informations n\'ont pas été vérifiées">Non validé</span>';
                } else {
                    echo '<span class="badge badge-success badge-pill float-right" data-toggle="tooltip" data-placement="top" title="Les informations de ce composants sont correctes">Validé</span>';
                }
                ?>
            </p>
            <hr>
            <?php foreach ($GLOBALS['cols'][$component['type']] as $key => $value) {
                echo '<b>' . $value['name'] . '</b> : ' . (isset($specs[$key]) ? (isset($value['values']) ? $value['values'][$specs[$key]] : $specs[$key]) : 'Inconnu') . ' ' . (isset($value['unit']) ? $value['unit'] : "") . '<br>';
            } ?>
        </div>
    </main>

    <?php include('includes/footer.php'); ?>
</body>

</html>