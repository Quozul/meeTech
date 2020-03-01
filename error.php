<html>
<?php include('includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">

    <?php include('includes/header.php'); ?>

    <main role="main" class="container">
        <div class="jumbotron">
            <h1>Error <?php echo $_GET['code']; ?></h1>
            <p class="lead">
                <?php
                switch ($_GET['code']) {
                    case '400':
                        echo 'Mauvaise requête';
                        break;
                    case '401':
                        echo 'Accès non autorisé';
                        break;
                    case '402':
                        echo 'Requiert un paiment';
                        break;
                    case '403':
                        echo 'Interdit';
                        break;
                    case '404':
                        echo 'Page non trouvée';
                        break;

                    case '500':
                        echo 'Erreur interne au serveur';
                        break;
                    case '502':
                        echo 'Mauvaise passerelle';
                        break;
                    case '503':
                        echo 'Service indisponible';
                        break;
                    case '504':
                        echo 'Délai dépassé';
                        break;
                    case '505':
                        echo 'Version HTTP non supportée';
                        break;

                    default:
                        echo 'Code erreur non supporté';
                        break;
                }
                ?>.

                Go back to <a href="/">home page</a>.
            </p>
        </div>
    </main>

    <?php include('includes/footer.php'); ?>

</body>

</html>