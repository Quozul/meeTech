<!DOCTYPE html>
<html>
<?php
$page_name = 'Branding';
include('includes/head.php');
?>

<body class="d-flex vh-100 flex-column justify-content-between">

    <?php include('includes/header.php'); ?>

    <main role="main" class="container">
        <h1>L'image du site</h1>
        <div class="jumbotron">
            <h2>Les couleurs</h2>
            <p class="lead">Voici les couleurs utilisées.</p>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Utilisation</th>
                            <th scope="col">Nom de couleur</th>
                            <th scope="col">Code couleur</th>
                            <th scope="col">Aspect</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">Arrière-plan</th>
                            <td>Beige</td>
                            <td>#FFE9C7<br>rgb(255, 233, 199)</td>
                            <td><span class="mt-color-background color-square">&nbsp;</span></td>
                        </tr>
                        <tr>
                            <th scope="row">Premier-plan (tête et pied de page)</th>
                            <td>Bleu acier</td>
                            <td>#4682B4<br>rgb(70, 130, 180)</td>
                            <td><span class="mt-color-foreground color-square">&nbsp;</span></td>
                        </tr>
                        <tr>
                            <th scope="row">Liens</th>
                            <td>Violet sombre</td>
                            <td>#2F0042<br>rgb(47, 0, 66)</td>
                            <td><span class="mt-color-link color-is-background color-square">&nbsp;</span></td>
                        </tr>
                        <tr>
                            <th scope="row">Bouton de recherche</th>
                            <td>Vert pomme</td>
                            <td>#28a745<br>rgb(40, 167, 69)</td>
                            <td><span class="mt-color-search color-square">&nbsp;</span></td>
                        </tr>
                        <tr>
                            <th scope="row">Fond des éléments</th>
                            <td>Blanc neige</td>
                            <td>#ffffff<br>rgb(255, 255, 255)</td>
                            <td><span class="mt-color-element color-square">&nbsp;</span></td>
                        </tr>
                        <tr>
                            <th scope="row">Texte principal</th>
                            <td>Noir pas noir</td>
                            <td>#373A3C<br>rgb(55, 58, 60)</td>
                            <td><span class="mt-color-text color-is-background color-square">&nbsp;</span></td>
                        </tr>
                        <tr>
                            <th scope="row">Texte secondaire</th>
                            <td>Gris foncé</td>
                            <td>#777777<br>rgb(119, 119, 119)</td>
                            <td><span class="mt-color-secondary-text color-is-background color-square">&nbsp;</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <hr>

            <h2>Logo</h2>
            <p class="lead">La version actuelle de notre logo :</p>
            <img src="/images/logov4.svg" alt="Second version of the logo" class="img-fluid rounded d-block w-25">

            <hr>

            <h2>Typographie</h2>
            <p class="text-justify">Nous utilisons la police d'écriture <a href="https://fonts.google.com/specimen/Quicksand" target="_blank">Quicksand</a> qui est disponible sur <a href="https://fonts.google.com/">Google Fonts</a>.</p>
        </div>
    </main>

    <?php include('includes/footer.php'); ?>

</body>

</html>