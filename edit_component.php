<!DOCTYPE html>
<html>
<?php include('includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">
    <?php include('includes/header.php'); ?>

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
            <form action="actions/hardware/update_component.php" method="post" id="submit-component">
                <?php include('includes/hardware/component_form.php'); ?>
                <input type="number" class="d-none" name="id" value="<?php echo $_POST['id']; ?>">
            </form>

            <button type="submit" class="btn btn-primary" onclick="send_update();">Proposer la modification</button>
        </div>

        <script>
            let specs = <?php echo $component['specifications']; ?>;
            let type = <?php echo "'" . $component['type'] . "'"; ?>;
            let sources = <?php echo "'" . $component['sources'] . "'"; ?>;

            document.getElementById('component-type').value = type;
            document.getElementById('component-sources').value = sources;

            display_form(document.getElementById('submit-components-group').children, type)

            for (const key in specs)
                if (specs.hasOwnProperty(key) && document.getElementsByName(key)[0])
                    document.getElementsByName(key)[0].value = specs[key];

            function send_update() {
                request('/actions/hardware/update_component.php', formToQuery('submit-component')).then((req) => {
                    console.log(req);
                    alert('Composant correctement mis à jour !\nVous pouvez retourner en arrière.');
                }).catch((e) => {
                    console.log(e.response);
                    if (e.status == 401)
                        alert('Impossible d\'effectuer cette action. Vérifiez que vous êtes bien connecté.');
                    else {
                        console.log(e);
                        alert('Une erreur est survenue. Contactez un administrateur avec le code d\'erreur :\n' + e.status);
                    }
                });
            }
        </script>

    </main>

    <?php include('includes/footer.php'); ?>
</body>

</html>