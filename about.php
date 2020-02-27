<!DOCTYPE html>
<html>
<?php
$page_name = 'About';
include('includes/head.php');
?>

<body class="d-flex vh-100 flex-column justify-content-between">

    <?php include('includes/header.php'); ?>

    <main role="main" class="container">
        <h1>A propos</h1>
        <div class="jumbotron">
            <h2>Description</h2>
            <p class="lead">
                Site de configuration d'ordinateur, d'informations sur les nouveautés en relation avec l'informatique et d'échange autour de ce domaine.
            </p>
            <p class="text-justify">
                Le site est actuellement encore en développement, si vous rencontrez tout problème, rejoignez le <a href="https://discord.gg/CfxuZ3J">serveur Discord</a> pour échanger avec les administrateurs et développeurs.
            </p>
            <p class="text-justify">
                Doté d’un système de comparaison permettant aux utilisateurs de trouver la configuration optimale de leur PC. Le site permet également à l’utilisateur, une fois identifié, d'ajouter des composants, d’exporter sa configuration sur le forum et d’en discuter avec les autres utilisateurs.
            </p>
            <p>Voir <a href="/brand/">l'image</a> du site.</p>

            <hr>

            <h2>Outils utilisés</h2>
            <p class="lead">Voici une liste des outils que nous utilisons pour développer le site :</p>
            <ul class="list">
                <li><b>Synchronisation du code :</b> GitHub</li>
                <li><b>Organisation des tâches :</b> Trello</li>
                <li><b>Logo :</b> Adobe Illustrator</li>
                <li><b>Design :</b> Figma, Adobe XD</li>
                <li><b>Développement :</b> Visual Studio Code, Sublime Text</li>
                <li><b>Hébergement :</b> Apache2, PHP, MAMP, XAMPP <i class="text-muted">(développement local)</i></li>
                <li><b>Base de donnée :</b> MariaDB</li>
            </ul>

            <hr>

            <h2>L'équipe</h2>
            <p class="lead">Voici les membres de l'équipe, actuellement composée de 3 développeurs :</p>
            <ul class="list">
                <!-- TODO: Add links to team members' profile -->
                <li><a href="" title="Lien non fonctionnel pour le moment">Sarah S. (Ellana)</a></li>
                <li><a href="" title="Lien non fonctionnel pour le moment">Antoine D. (Revan)</a></li>
                <li><a href="" title="Lien non fonctionnel pour le moment">Erwan L. (Quozul)</a></li>
            </ul>
        </div>
    </main>

    <?php include('includes/footer.php'); ?>

</body>

</html>