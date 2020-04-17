<!DOCTYPE html>
<html>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

    <main class="container">
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/hardware/new_component_modal.php'); ?>

        <h1>Commentaires</h1>

        <div class="jumbotron" id="list"><?php include('includes/comments.php'); ?></div>

        <script>
            const update_list = function() {
                document.location.reload();
            }
        </script>
    </main>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

</body>

</html>