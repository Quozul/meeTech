<!DOCTYPE html>
<html>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

    <main class="container">
        <h1>Configurateur</h1>
        <div class="jumbotron">
            <?php $req = $pdo->prepare('SELECT name, id_t FROM component_type');
            $req->execute();
            $types = $req->fetchAll();

            foreach ($types as $key => $type) { ?>
                <label><?php echo $type['name']; ?></label>
                <select name="<?php echo $type['id_t']; ?>" class="form-control mb-3">
                    <?php $req = $pdo->prepare('SELECT id_c, brand, name FROM component WHERE type = ?');
                    $req->execute([$type['id_t']]);
                    $components = $req->fetchAll();

                    foreach ($components as $key => $component) { ?>
                        <option value="<?php echo $component['id_c']; ?>"><?php echo $component['brand'] . ' ' . $component['name']; ?></option>
                    <?php } ?>
                </select>
            <?php } ?>
        </div>
    </main>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

</body>

</html>