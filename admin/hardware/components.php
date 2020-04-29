<!DOCTYPE html>
<html lang="fr">
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

    <main class="container">
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/hardware/new_component_modal.php'); ?>

        <button class="btn btn-success float-right" data-toggle="modal" data-target="#add-component-modal">Ajouter un composant</button>
        <h1>Types de composants</h1>

        <div class="jumbotron" id="list"><?php include('includes/component_list.php'); ?></div>

        <script>
            const update_list = page_add_component = function() {
                document.location.reload();
                /*getHtmlContent('/admin/hardware/includes/component_list.php', '').then((res) => {
                    document.getElementById('list').innerHTML = res;
                }).catch(e => console.log);
            }

            function remove_component(id) {
                request('/admin/hardware/actions/remove_component.php', 'id=' + id).then((res) => {
                    update_list();
                });
            }

            function validate_component(id) {
                request('/admin/hardware/actions/validate_component.php', 'id=' + id).then((res) => {
                    update_list();
                });*/
            }
        </script>
    </main>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

</body>

</html>