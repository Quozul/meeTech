<?php
include('config.php');

// Columns names and description
$cols = json_decode(file_get_contents('includes/hardware/specifications.json'), true);
?>

<!DOCTYPE html>
<html>
<?php include('includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">
    <?php include('includes/header.php'); ?>

    <main role="main" class="container h-100">
        <h1>Configurateur</h1>

        <div class="jumbotron">
            <?php foreach (['cpu' => 'Processeur', 'gpu' => 'Carte graphique', 'ram' => 'Mémoire vive', 'ssd' => 'SSD', 'hdd' => 'Disque dur', 'mb' => 'Carte mère'] as $type => $name) { ?>
                <div class="input-group mb-3">
                    <div class="input-group-prepend col-2 d-block p-0">
                        <span class="input-group-text"><?php echo $name; ?></span>
                    </div>
                    <select class="form-control selectpicker" id="<?php echo $type; ?>" data-live-search="true">
                        <option value="">Selectionnez un <?php echo $name; ?>...</option>
                        <?php
                        $sth = $pdo->prepare('SELECT * FROM component WHERE type = ?');
                        $sth->execute([$type]);
                        $result = $sth->fetchAll();

                        foreach ($result as $key => $value) {
                            $specs = json_decode($value['specifications'], true);
                        ?>
                            <option value="<?php echo $value['id']; ?>" <?php if (!empty($_GET[$type]) && $_GET[$type] == $value['id']) echo 'selected'; ?>><?php echo $specs['brand'] . ' ' . $specs['name']; ?></option>
                        <?php } ?>
                    </select>
                    <div class="input-group-append d-block p-0">
                        <?php if (!empty($_GET[$type])) { ?>

                            <div class="modal fade" tabindex="-1" role="dialog" id="<?php echo $type; ?>-modal">
                                <?php
                                $sth = $pdo->prepare('SELECT * FROM component WHERE id = ?');
                                $sth->execute([$_GET[$type]]);
                                $component = $sth->fetch();
                                $specs = json_decode($component['specifications'], true);
                                ?>
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><?php echo (isset($specs['brand']) ? $specs['brand'] : 'NoBrand') . ' ' . (isset($specs['name']) ? $specs['name'] : 'NoName'); ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <ul class="list-group">
                                                <?php foreach ($cols[$type] as $key => $value) { ?>
                                                    <li class="list-group-item">
                                                        <?php echo '<b>' . $value['name'] . '</b> : ' . (isset($specs[$key]) ? (isset($value['values']) ? $value['values'][$specs[$key]] : $specs[$key]) : 'Inconnu') . ' ' . (isset($value['unit']) ? $value['unit'] : "") . '<br>'; ?>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php } ?>

                        <button class="btn btn-outline-secondary <?php if (empty($_GET[$type])) echo 'disabled'; ?>" type="button" data-toggle="modal" data-target="#<?php echo $type; ?>-modal">Infos</button>
                    </div>
                </div>
            <?php } ?>
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