<!DOCTYPE html>
<html>
<?php include('../includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">
    <?php include('../includes/header.php'); ?>

    <main class="container">
        <h1>Bienvenue dans le back office !
            <!-- Utilisateur -->
        </h1>

        <div class="jumbotron">
            <p class="lead">Voici une liste de pages sur lesquelles vous pouvez administrer le site</p>
            <ul>
                <li><a href="/admin/list_users/">Utilisateurs</a></li>
                <li><a href="/admin/list_badge/">Badges</a></li>
                <li><a href="/admin/languages/">Langages</a></li>
                <li><a href="/admin/categories/">Catégories</a></li>

                <hr>

                <h3>Composants</h3>
                <li><a href="/admin/hardware/components/">Composants</a></li>
                <li><a href="/admin/hardware/component_types/">Types de composants</a></li>
                <li><a href="/admin/hardware/comments/">Commentaires des composants</a></li>

                <hr>

                <a href="/admin/actions/xml_dump/" download="meetech.xml">Télécharger la base de données</a>
            </ul>
        </div>
    </main> <?php include('../includes/footer.php'); ?> </body>

</html>