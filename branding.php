<!DOCTYPE html>
<html>
    <?php include('includes/head.php'); ?>

    <body>
        <?php include('includes/header.php'); ?>

        <main role="main" class="container">
            <div class="jumbotron">
                <h1>Colors</h1>
                <p class="lead">Here are the colors used on our website.</p>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Usage</th>
                                <th scope="col">Color name</th>
                                <th scope="col">Color code</th>
                                <th scope="col">Aspect</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Background</th>
                                <td>Sky blue</td>
                                <td>#87CEEB<br>rgb(135, 206, 235)</td>
                                <td><span class="background-color color-square">&nbsp;</span></td>
                            </tr>
                            <tr>
                                <th scope="row">Foreground (header/footer)</th>
                                <td>Steel blue</td>
                                <td>#4682B4<br>rgb(70, 130, 180)</td>
                                <td><span class="foreground-color color-square">&nbsp;</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="jumbotron">
                <h1>Logo</h1>
                <p class="lead"><i>Need to draw a logo.</i> Current sketch of our logo:</p>
                <img src="assets/logov2.svg" alt="Second version of the logo" class="img-fluid rounded">
            </div>

            <div class="jumbotron">
                <h1>Typography</h1>
                <p class="lead">We haven't chosen a typography yet.</p>
            </div>
        </main>

        <?php include('includes/footer.php'); ?>

    </body>

</html>
