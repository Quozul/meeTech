<!DOCTYPE html>
<html>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

    <main class="container">
        <div class="modal fade" id="add-type-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter un type de composant</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php include('includes/new_type_form.php'); ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-add-type-modal">Annuler</button>
                        <!-- <button type="button" class="btn btn-primary" onclick="add_type();">Ajouter</button> -->
                        <button type="submit" class="btn btn-primary" form="add-type-form">Ajouter</button>
                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn-success float-right" data-toggle="modal" data-target="#add-type-modal">Ajouter un type</button>
        <h1>Types de composants</h1>

        <div class="jumbotron" id="list"><?php include('includes/type_list.php'); ?></div>

        <script>
            function update_list() {
                document.location.reload();
            }
        </script>
    </main>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

</body>

</html>
