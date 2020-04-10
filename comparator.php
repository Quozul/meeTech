<!DOCTYPE html>
<html>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

    <main class="container">
        <h1>Comparateur</h1>
        <div class="jumbotron">
            <?php $req = $pdo->prepare('SELECT name, id_t FROM component_type');
            $req->execute();
            $types = $req->fetchAll();

            foreach ($types as $key => $type) { ?>

            <?php } ?>
        </div>
    </main>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

</body>

</html>