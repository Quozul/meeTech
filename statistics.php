<!DOCTYPE html>
<html>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>
    <script src='/scripts/stats.js'></script>

    <main class="container">
        <h1>Statistiques de visites</h1>
        <div class="jumbotron">
            <h3>Visites de pages</h3>
            <canvas id="visits" style="width: 100%;"></canvas>
            <h3>Visites par heures</h3>
            <canvas id="time" style="width: 100%;"></canvas>
            <script>
                const page_stats = new Statistics();

                const visits_raw = JSON.parse(
                    <?php $req = $pdo->prepare("SELECT SUBSTRING_INDEX(REPLACE(REPLACE(page, '/', ''), '.php', ''), '?', 1) as p, visits FROM page_visit WHERE LENGTH(page) - LENGTH(REPLACE(page, '/', '')) = 1 GROUP BY p ORDER BY visits DESC LIMIT 10");
                    $req->execute();
                    echo "'" . json_encode($req->fetchAll()) . "'"; ?>);

                let visits = [];
                console.log(visits_raw);

                for (const key in visits_raw)
                    if (visits_raw.hasOwnProperty(key)) {
                        const e = visits_raw[key];
                        visits.push([e.p, e.visits]);
                    }

                page_stats.setStats(visits);
                page_stats.setOptions({
                    bg_color: 'white',
                    fill_color: 'black'
                })
                page_stats.setChartType('bars');
                page_stats.draw(document.getElementById('visits'), .5);


                const time_stats = new Statistics();

                const time_raw = JSON.parse(
                    <?php $req = $pdo->prepare('SELECT extract(HOUR from date) as hour, sum(visits) as visits FROM time_visit group by hour');
                    $req->execute();
                    echo "'" . json_encode($req->fetchAll()) . "'"; ?>);


                let time = [];

                for (const key in time_raw)
                    if (time_raw.hasOwnProperty(key)) {
                        const e = time_raw[key];
                        time.push([e.hour, e.visits]);
                    }

                console.log(time);

                time_stats.setStats(time);
                time_stats.setOptions({
                    bg_color: 'white',
                    fill_color: 'black'
                })
                time_stats.setChartType('bars');
                time_stats.draw(document.getElementById('time'), .5);
            </script>
        </div>
    </main>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

</body>

</html>