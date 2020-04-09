<!DOCTYPE html>
<html>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>
    <script src='/scripts/stats.js'></script>

    <main class="container">
        <h1>Statistiques de visites</h1>
        <div class="jumbotron">
            <canvas id="visits" style="width: 100%;"></canvas>
            <script>
                const stats = new Statistics();

                const visits_raw = JSON.parse(
                    <?php $req = $pdo->prepare('SELECT page, visits FROM page_visit ORDER BY visits DESC LIMIT 10');
                    $req->execute();
                    echo "'" . json_encode($req->fetchAll()) . "'"; ?>);

                let visits = [];

                for (const key in visits_raw)
                    if (visits_raw.hasOwnProperty(key)) {
                        const e = visits_raw[key];
                        visits.push([e.page, e.visits]);
                    }

                stats.setStats(visits);
                stats.setOptions({
                    bg_color: 'white',
                    fill_color: 'black'
                })
                stats.setChartType('bars');
                stats.draw(document.getElementById('visits'), 1);
            </script>
        </div>
    </main>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

</body>

</html>