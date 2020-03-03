<!DOCTYPE html>
<html>
<?php include('../includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">
    <?php include('../includes/header.php'); ?>

    <main class="container">
        <h1>Bienvenue dans le back office!
            <!-- Utilisateur -->
        </h1>

        <div class="jumbotron">
            <p class="lead">Voici une liste de pages sur lesquelles vous pouvez administrer le site</p>
            <ul>
                <li><a href="/admin/list_users/">Utilisateurs</a></li>
                <li><a href="/admin/languages/">Langages</a></li>
                <li><a href="/admin/components/">Composants</a></li>
            </ul>
        </div>
    </main>

    <?php include('../includes/footer.php'); ?>
</body>

</html>