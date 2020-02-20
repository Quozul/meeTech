<?php
include('config.php');
$page_limit = 3;
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Columns names and description
$GLOBALS['cols'] = json_decode(file_get_contents('includes/hardware/specifications.json'), true);
?>

<!DOCTYPE html>
<html>
<?php include('includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">
    <?php include('includes/header.php'); ?>

    <main role="main" class="container">
        <!-- Get component's informations -->
        <?php
        $sth = $pdo->prepare('SELECT * FROM component WHERE id = ?');
        $sth->execute([$_GET['id']]);
        $component = $sth->fetch();
        $specs = json_decode($component['specifications'], true);
        ?>

        <h1>Composant</h1>
        <div class="jumbotron">
            <h2><?php echo $specs['brand'] . ' ' . $specs['name'] ?></h2>
            <hr>
            <?php foreach ($GLOBALS['cols'][$component['type']] as $key => $value) {
                echo '<b>' . $value['name'] . '</b> : ' . (isset($specs[$key]) ? (isset($value['values']) ? $value['values'][$specs[$key]] : $specs[$key]) : 'Inconnu') . ' ' . (isset($value['unit']) ? $value['unit'] : "") . '<br>';
            } ?>
        </div>
    </main>

    <?php include('includes/footer.php'); ?>
</body>

</html>