<?php
include('config.php');

// Columns names and description
$cols = json_decode(file_get_contents('includes/hardware/specifications.json'), true);

$config_name = isset($_GET['name']) && !empty($_GET['name']) ? $_GET['name'] : 'Config sans nom';
unset($_GET['name']);
$score = 0;

foreach ($_GET as $type => $id) {
    if (empty($id)) continue;
    $sth = $pdo->prepare('SELECT * FROM component WHERE id = ?');
    $sth->execute([$id]);
    $component = $sth->fetch();

    $score += $component['score'];
}

$page_name = $config_name;

$page_description = $config_name . ' - ' . $score . ' points.';
?>

<!DOCTYPE html>
<html>
<?php include('includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">
    <?php include('includes/header.php'); ?>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.9/css/bootstrap-select.min.css" />

    <main role="main" class="container">
        <h1>Configurateur</h1>

        <div class="jumbotron">
            <!-- Config name -->
            <div class="input-group mb-3">
                <div class="input-group-prepend col-2 d-block p-0">
                    <span class="input-group-text">Nom</span>
                </div>
                <input id="config-name" class="form-control" name="name" type="text" value="<?php echo $config_name; ?>">

                <div class="input-group-append">
                    <span class="input-group-text">Score : <?php echo $score; ?></span>
                </div>
            </div>

            <hr>

            <?php foreach ($cols as $type => $name) { ?>
                <div class="input-group mb-3">
                    <div class="input-group-prepend col-2 d-block p-0">
                        <span class="input-group-text"><?php echo $name['name']; ?></span>
                    </div>

                    <!-- Component select -->
                    <select class="form-control selectpicker" id="<?php echo $type; ?>" data-live-search="true" data-show-subtext="true">
                        <option value="">Selectionnez un <?php echo strtolower($name['name']); ?>...</option>
                        <?php
                        $sth = $pdo->prepare('SELECT * FROM component WHERE type = ? ORDER BY score DESC ');
                        $sth->execute([$type]);
                        $result = $sth->fetchAll();

                        foreach ($result as $key => $value) {
                            $specs = json_decode($value['specifications'], true);
                        ?>
                            <option data-tokens="<?php echo $specs['id']; ?>" value="<?php echo $value['id']; ?>" <?php if (!empty($_GET[$type]) && $_GET[$type] == $value['id']) echo 'selected'; ?>>
                                <?php echo $specs['brand'] . ' ' . $specs['name']; ?>
                            </option>
                        <?php } ?>
                    </select>

                    <!-- Component informations -->
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
                                                <?php foreach ($cols[$type]['specs'] as $key => $value) { ?>
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

            <hr>

            <!-- Copy button -->
            <button type="button" class="btn btn-primary" onclick="copy();">Copier le lien</button>
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

                    // TODO: reload info modal with ajax
                    window.location.href = url.href.substr(0, url.href.lastIndexOf('?')) + '?' + params.toString();
                }
            }

        document.getElementById('config-name').onchange = function() {
            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search.slice(1));
            params.set('name', this.value);

            window.history.pushState({}, '', url.href.substr(0, url.href.lastIndexOf('?')) + '?' + params.toString());
        };

        function copy() {
            console.log('copy!');
            navigator.clipboard.writeText(window.location.href).then(function() {
                /* clipboard write success */
            }, function() {
                /* clipboard write failed */
            });
        }
    </script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.9/js/bootstrap-select.min.js"></script>

    <?php include('includes/footer.php'); ?>
</body>

</html>