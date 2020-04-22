<!DOCTYPE html>
<html>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

    <main class="container">
        <h1>Configurateur</h1>
        <div class="jumbotron">
            <span id="total_score" class="float-right">Score total : 0</span>
            <form id="components">
                <?php $req = $pdo->prepare('SELECT name, id_t FROM component_type');
                $req->execute();
                $types = $req->fetchAll();

                foreach ($types as $key => $type) { ?>
                    <label><?php echo $type['name']; ?></label>
                    <div class="input-group mb-3">

                        <?php $req = $pdo->prepare('SELECT id_c, brand, name FROM component WHERE type = ?');
                        $req->execute([$type['id_t']]);
                        $components = $req->fetchAll(); ?>

                        <select name="<?php echo $type['id_t']; ?>" class="component_select form-control" <?php echo count($components) == 0 ? 'disabled' : ''; ?>>
                            <option value="" selected>Sélectionnez...</option>
                            <?php foreach ($components as $key => $component) { ?>
                                <option value="<?php echo $component['id_c']; ?>"><?php echo $component['brand'] . ' ' . $component['name']; ?></option>
                            <?php } ?>
                        </select>

                        <div class="input-group-append">
                            <div class="modal fade" id="component-<?php echo $type['id_t']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><?php echo $type['name']; ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" id="modal-body-<?php echo $type['id_t']; ?>">
                                            <p>Veuillez sélectionner un composant pour voir ses informations</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <span class="input-group-text" data-toggle="modal" data-target="#component-<?php echo $type['id_t']; ?>">Infos</span>
                        </div>
                    </div>
                <?php } ?>
            </form>
        </div>

        <script>
            const selects = document.getElementsByClassName('component_select');

            for (const key in selects)
                if (selects.hasOwnProperty(key))
                    selects[key].addEventListener('change', function() {
                        getHtmlContent('/includes/hardware/config_score.php', formToQuery('components')).then((res) => {
                            document.getElementById('total_score').innerHTML = 'Score total : ' + res;
                        });

                        getHtmlContent('/includes/hardware/component_resume.php', 'id=' + this.value).then((res) => {
                            document.getElementById('modal-body-' + this.name).innerHTML = res;
                        });
                    });
        </script>
    </main>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

</body>

</html>