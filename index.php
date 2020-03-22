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
            $req = $pdo->prepare('SELECT id_c, brand, name, added_by, added_date, type FROM component ORDER BY added_date DESC LIMIT 3');
            $req->execute();
            $components = $req->fetchAll();

            foreach ($components as $key => $component) {
                $req = $pdo->prepare('SELECT name, value, unit FROM specification JOIN specification_list ON specification.specification = specification_list.id_s WHERE component = ?');
                $req->execute([$component['id_c']]);
                $specs = $req->fetchAll();

                $req = $pdo->prepare('SELECT name FROM component_type where id_t = ?');
                $req->execute([$component['type']]);
                $type = $req->fetch()[0];
            ?>
                <div class="card" style="width: 18rem;">
                    <?php if (isset($component['image'])) { ?>
                        <img class="card-img-top" src="" alt="Card image cap">
                    <?php } ?>

                    <div class="card-header">
                        <h5 class="card-title"><?php echo $component['brand'] . ' ' . $component['name']; ?></h5>
                        <p class="card-subtitle text-muted"><?php echo $type; ?></p>
                    </div>

                    <ul class="list-group list-group-flush">
                        <?php if (count($specs) == 0) { ?>
                            <li class="list-group-item">Il n'y a aucune informations sur ce composant.</li>
                            <li class="list-group-item text-center">
                                <form action="/edit_component.php" method="post">
                                    <button type="submit" class="btn btn-sm btn-primary" name="id" value="<?php echo $component['id']; ?>">Proposer une modification</button>
                                </form>
                            </li>
                            <?php } else {
                            foreach ($specs as $key => $spec) {
                                if (!isset($specs[$key])) continue; ?>
                                <li class="list-group-item">
                                    <?php echo '<b>' . $spec['name'] . '</b> : ' . $spec['value'] . ' ' . $spec['unit']; ?><br>
                                </li>
                        <?php
                                if ($key == 3) break; // limit specifications to the 3 firsts one
                            }
                        } ?>
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