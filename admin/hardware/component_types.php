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
                        <button type="button" class="btn btn-primary" onclick="add_type();">Ajouter</button>
                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn-success float-right" data-toggle="modal" data-target="#add-type-modal">Ajouter un type</button>
        <h1>Types de composants</h1>

        <div class="jumbotron" id="list"></div>

        <script>
            function add_type() {
                request('/admin/hardware/actions/add_type.php', formToQuery('add-type-form')).then((res) => {
                    console.log(res.response);
                    if (res.response.includes('type_already_exists')) alert('Le composant que vous essayez d\'ajouter existe déjà.\nModifiez son nom ou abandonnez.');
                    else {
                        // document.getElementById('close-add-type-modal').click();
                        update_list();
                    }
                });
            }

            function remove_type(id) {
                request('/admin/hardware/actions/remove_type.php', `id=${id}`).then((res) => {
                    document.getElementById('component-type-row-' + id).remove();
                    alert('Type de composant supprimé avec succès !');
                    update_list();
                });
            }

            function update_list() {
                getHtmlContent('/admin/hardware/includes/type_list.php', '').then((res) => {
                    document.getElementById('list').innerHTML = res;
                }).catch(e => console.log);
            }

            update_list();
        </script>
    </main>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

</body>

</html>