<?php
include('config.php');
// Columns components' specifications names and description
$GLOBALS['cols'] = json_decode(file_get_contents('includes/hardware/specifications.json'), true);
?>
<!DOCTYPE html>
<html>
<?php include('includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">
    <?php include('includes/header.php'); ?>

    <main role="main" class="container">
        <?php
        // Verify is there is at least 1 event
        $sth = $pdo->prepare('SELECT COUNT(*) FROM event');
        $sth->execute();
        $count = $sth->fetch()[0];
        if ($count > 0) {
        ?>
            <h1>Evénements</h1>
            <div class="card text-center">
                <div class="card-header">
                    Evénement
                </div>
                <div class="card-body">
                    <h5 class="card-title">Nom de l'événement</h5>
                    <p class="card-text">Description de l'énévement.</p>
                    <a href="#" class="btn btn-primary">En savoir plus</a>
                </div>
                <div class="card-footer text-muted">
                    2 days ago
                </div>
            </div>
        <?php } ?>

        <h1>Nouveaux composants ajoutés</h1>
        <div class="d-flex flex-row justify-content-around">
            <?php
            // Verify is there is at least 1 event
            $sth = $pdo->prepare('SELECT * FROM component LIMIT 3');
            $sth->execute();
            $result = $sth->fetchAll();

            foreach ($result as $key => $component) {
                $specs = json_decode($component['specifications'], true);
            ?>
                <div class="card" style="width: 18rem;">
                    <?php if (isset($component['image'])) { ?>
                        <img class="card-img-top" src="" alt="Card image cap">
                    <?php } ?>
                    <div class="card-header">
                        <h5 class="card-title"><?php echo $specs['brand'] . ' ' . $specs['name']; ?></h5>
                        <p class="card-subtitle text-muted"><?php switch ($component['type']) {
                                                                case 'cpu':
                                                                    echo 'Processeur';
                                                                    break;
                                                                case 'gpu':
                                                                    echo 'Carte graphique';
                                                                    break;
                                                                case 'ram':
                                                                    echo 'Mémoire vive';
                                                                    break;
                                                                case 'hdd':
                                                                    echo 'Disque dur';
                                                                    break;
                                                                case 'ssd':
                                                                    echo 'SSD';
                                                                    break;
                                                                case 'mb':
                                                                    echo 'Carte mère';
                                                                    break;
                                                            } ?></p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($GLOBALS['cols'][$component['type']] as $key => $value) { ?>
                            <li class="list-group-item"><?php echo '<b>' . $value['name'] . '</b> : ' . (isset($specs[$key]) ? $specs[$key] : 'Inconnu') . ' ' . (isset($value['unit']) ? $value['unit'] : ''); ?></li>
                        <?php } ?>
                    </ul>
                    <div class="card-body">
                        <a href="<?php echo '/view_component.php?id=' . $component['id']; ?>" class="card-link">Découvrir</a>
                        <a href="#" class="card-link disabled">Comparer</a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>

    <?php include('includes/footer.php'); ?>

</body>

</html>