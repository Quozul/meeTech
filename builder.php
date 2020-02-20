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

    <main role="main" class="container h-100">
        <h1>Configurateur</h1>

        <div class="jumbotron">
            <div class="input-group mb-3">
                <div class="input-group-prepend w-25 d-block">
                    <span class="input-group-text">Processeur</span>
                </div>
                <select class="form-control selectpicker" id="cpu" data-live-search="true">
                    <?php
                    $sth = $pdo->prepare('SELECT * FROM component WHERE type = ?');
                    $sth->execute(['cpu']);
                    $result = $sth->fetchAll();

                    foreach ($result as $key => $value) {
                        $specs = json_decode($value['specifications'], true);
                    ?>
                        <option value="<?php echo $value['id']; ?>" <?php if (isset($_GET['cpu']) && $_GET['cpu'] == $value['id']) echo 'selected'; ?>><?php echo $specs['brand'] . ' ' . $specs['name']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend w-25 d-block">
                    <span class="input-group-text">Carte graphique</span>
                </div>
                <select class="form-control selectpicker" id="gpu" data-live-search="true">
                    <?php
                    $sth = $pdo->prepare('SELECT * FROM component WHERE type = ?');
                    $sth->execute(['gpu']);
                    $result = $sth->fetchAll();

                    foreach ($result as $key => $value) {
                        $specs = json_decode($value['specifications'], true);
                    ?>
                        <option value="<?php echo $value['id']; ?>" <?php if (isset($_GET['gpu']) && $_GET['gpu'] == $value['id']) echo 'selected'; ?>><?php echo $specs['brand'] . ' ' . $specs['name']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend w-25 d-block">
                    <span class="input-group-text">Carte graphique</span>
                </div>
                <select class="form-control selectpicker" id="ram" data-live-search="true">
                    <?php
                    $sth = $pdo->prepare('SELECT * FROM component WHERE type = ?');
                    $sth->execute(['ram']);
                    $result = $sth->fetchAll();

                    foreach ($result as $key => $value) {
                        $specs = json_decode($value['specifications'], true);
                    ?>
                        <option value="<?php echo $value['id']; ?>" <?php if (isset($_GET['ram']) && $_GET['ram'] == $value['id']) echo 'selected'; ?>><?php echo $specs['brand'] . ' ' . $specs['name']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend w-25 d-block">
                    <span class="input-group-text">SSD</span>
                </div>
                <select class="form-control selectpicker" id="ssd" data-live-search="true">
                    <?php
                    $sth = $pdo->prepare('SELECT * FROM component WHERE type = ?');
                    $sth->execute(['ssd']);
                    $result = $sth->fetchAll();

                    foreach ($result as $key => $value) {
                        $specs = json_decode($value['specifications'], true);
                    ?>
                        <option value="<?php echo $value['id']; ?>" <?php if (isset($_GET['ssd']) && $_GET['ssd'] == $value['id']) echo 'selected'; ?>><?php echo $specs['brand'] . ' ' . $specs['name']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend w-25 d-block">
                    <span class="input-group-text">Disque dur</span>
                </div>
                <select class="form-control selectpicker" id="hdd" data-live-search="true">
                    <?php
                    $sth = $pdo->prepare('SELECT * FROM component WHERE type = ?');
                    $sth->execute(['hdd']);
                    $result = $sth->fetchAll();

                    foreach ($result as $key => $value) {
                        $specs = json_decode($value['specifications'], true);
                    ?>
                        <option value="<?php echo $value['id']; ?>" <?php if (isset($_GET['hdd']) && $_GET['hdd'] == $value['id']) echo 'selected'; ?>><?php echo $specs['brand'] . ' ' . $specs['name']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend w-25 d-block">
                    <span class="input-group-text">Carte mère</span>
                </div>
                <select class="form-control selectpicker" id="mb" data-live-search="true">
                    <?php
                    $sth = $pdo->prepare('SELECT * FROM component WHERE type = ?');
                    $sth->execute(['mb']);
                    $result = $sth->fetchAll();

                    foreach ($result as $key => $value) {
                        $specs = json_decode($value['specifications'], true);
                    ?>
                        <option value="<?php echo $value['id']; ?>" <?php if (isset($_GET['mb']) && $_GET['mb'] == $value['id']) echo 'selected'; ?>><?php echo $specs['brand'] . ' ' . $specs['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </main>

    <script>
        const selects = document.getElementsByTagName('select');

        for (const key in selects)
            if (selects.hasOwnProperty(key)) {
                const slct = selects[key];
                slct.onchange = function() {
                    let url = new URL(window.location.href);
                    let params = new URLSearchParams(url.search.slice(1));
                    params.set(this.id, this.value);

                    window.location.href = url.href.substr(0, url.href.lastIndexOf('?')) + '?' + params.toString();
                }
            }
    </script>

    <?php include('includes/footer.php'); ?>
</body>

</html>