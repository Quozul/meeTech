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
                        echo 'Bad Request';
                        break;
                    case '401':
                        echo 'Unauthorized ';
                        break;
                    case '402':
                        echo 'Payment Required';
                        break;
                    case '403':
                        echo 'Forbidden';
                        break;
                    case '404':
                        echo 'Not Found';
                        break;

                    case '500':
                        echo 'Internal Server Error';
                        break;
                    case '502':
                        echo 'Bad Gateway';
                        break;
                    case '503':
                        echo 'Service Unavailable';
                        break;
                    case '504':
                        echo 'Gateway Timeout';
                        break;
                    case '505':
                        echo 'HTTP Version Not Supported';
                        break;

                    default:
                        echo 'Unhandled Error code';
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