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

        <h1>Edition du composant n°<?php echo $_POST['id']; ?></h1>
        <form action="../includes/hardware/edit_component.php" method="post" id="submit-component">
            <?php include('../includes/hardware/new_component.php'); ?>
        </form>

        <button type="submit" class="btn btn-primary" form="submit-component" name="id" value="<?php echo $_POST['id']; ?>">Proposer le composant</button>

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