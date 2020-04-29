<!DOCTYPE html>
<html lang="fr">
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

    <main class="container">
        <h1>Comparateur</h1>

        <?php $req = $pdo->prepare('SELECT name, id_t FROM component_type');
        $req->execute();
        $types = $req->fetchAll(); ?>

        <div class="jumbotron">
            <div class="row">
                <div class="col-12">
                    <select name="type" id="component-type" class="form-control mb-2">
                        <option selected>Selectionnez un type de composant à comparer</option>
                        <?php foreach ($types as $key => $type) { ?>
                            <option value="<?php echo $type['id_t']; ?>"><?php echo $type['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-6">
                    <h4>Composant a</h4>
                    <select name="name" id="component-a" class="form-control mb-2" disabled></select>
                </div>

                <div class="col-6">
                    <h4>Composant b</h4>
                    <select name="name" id="component-b" class="form-control mb-2" disabled></select>
                </div>
            </div>
        </div>

        <div class="jumbotron">
            <div class="row" id="comparison">
                <h3>Sélectionnez 2 composants</h3>
            </div>
        </div>
    </main>

    <script>
        function getCompList() {
            getHtmlContent('/includes/hardware/comparator_list.php', 'type=' + this.value).then((res) => {
                comp_a.innerHTML = comp_b.innerHTML = res;
                comp_a.disabled = comp_b.disabled = false;
            });
        }

        function getComp() {
            getHtmlContent('/includes/hardware/comparator.php', `type=${type.value}&compa=${comp_a.value}&compb=${comp_b.value}`).then((res) => {
                document.getElementById('comparison').innerHTML = res;
            });
        }

        const comp_a = document.getElementById('component-a');
        const comp_b = document.getElementById('component-b');
        const type = document.getElementById('component-type');

        type.addEventListener('change', getCompList);

        comp_a.addEventListener('change', getComp);
        comp_b.addEventListener('change', getComp);
    </script>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

</body>

</html>