<?php include('../config.php'); ?>

<!DOCTYPE html>
<html>
<?php include('../includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">
    <?php include('../includes/header.php'); ?>

    <main role="main" class="container">
        <!-- Get component informations -->
        <?php
        $sth = $pdo->prepare('SELECT * FROM component WHERE id = ?');
        $sth->execute([$_POST['id']]);
        $component = $sth->fetch();
        ?>

        <h1>
            <a class="btn btn-primary" role="button" href="javascript: history.go(-1)">« Retour</a>
            Edition du composant n°<?php echo $_POST['id']; ?>
        </h1>
        <div class="jumbotron">
            <?php if ($component['validated'] == 1) { ?>
                <div class="alert alert-warning" role="alert">Attention ! Ce composant a été validé, êtes-vous certain(e) de vouloir le modifier ?</div>
            <?php } ?>
            <span class="badge badge-primary float-right">Score : <?php echo $component['score']; ?></span>
            <form action="../includes/hardware/update_component.php" method="post" id="submit-component">
                <?php include('../includes/hardware/component_form.php'); ?>
            </form>

            <button type="submit" class="btn btn-primary" form="submit-component" name="id" value="<?php echo $_POST['id']; ?>">Proposer la modification</button>
        </div>

        <script>
            let specs = <?php echo $component['specifications']; ?>;
            let type = <?php echo "'" . $component['type'] . "'"; ?>

            console.log(specs);

            console.log(type);

            document.getElementById('component-type').value = type;

            display_form(document.getElementById('submit-components-group').children, type)

            for (const key in specs) {
                console.log(key);
                if (specs.hasOwnProperty(key) && document.getElementsByName(key)[0]) {
                    document.getElementsByName(key)[0].value = specs[key];
                }
            }
        </script>

    </main>

    <?php include('../includes/footer.php'); ?>
</body>

</html>