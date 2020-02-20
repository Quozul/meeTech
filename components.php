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
        <!-- <div class="jumbotron"> -->
        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#exampleModalCenter">Proposer un composant</button>

        <!-- Add component modal/form -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Ajout d'un composant</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="submit-component" method="post" action="/includes/hardware/add_component/" autocomplete="off">
                            <?php include('includes/hardware/new_component.php'); ?>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary" form="submit-component">Proposer le composant</button>
                    </div>
                </div>
            </div>
        </div>

        <h2>Composants</h2>
        <hr>

        <div class="row">
            <div class="col-3">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active" id="v-pills-cpu-tab" data-toggle="pill" href="#v-pills-cpu" role="tab" aria-controls="v-pills-cpu" aria-selected="true">Processeurs</a>
                    <a class="nav-link" id="v-pills-gpu-tab" data-toggle="pill" href="#v-pills-gpu" role="tab" aria-controls="v-pills-gpu" aria-selected="false">Cartes graphiques</a>
                    <a class="nav-link" id="v-pills-ram-tab" data-toggle="pill" href="#v-pills-ram" role="tab" aria-controls="v-pills-ram" aria-selected="false">Mémoire vive</a>
                    <a class="nav-link" id="v-pills-hdd-tab" data-toggle="pill" href="#v-pills-hdd" role="tab" aria-controls="v-pills-hdd" aria-selected="false">Disques durs</a>
                    <a class="nav-link" id="v-pills-ssd-tab" data-toggle="pill" href="#v-pills-ssd" role="tab" aria-controls="v-pills-ssd" aria-selected="false">SSDs</a>
                    <a class="nav-link" id="v-pills-mb-tab" data-toggle="pill" href="#v-pills-mb" role="tab" aria-controls="v-pills-mb" aria-selected="false">Cartes mère</a>
                </div>
            </div>

            <div class="col-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <?php
                    function component_table($table_name, $pdo, $page_limit, $page)
                    {
                        $sth = $pdo->prepare('SELECT COUNT(*) FROM component WHERE type = ?');
                        $sth->execute([$table_name]);
                        $content_count = $sth->fetch()[0];

                        if ($content_count > 0) {
                            $sth = $pdo->prepare('SELECT * FROM component WHERE type = ? LIMIT ? OFFSET ?');
                            $sth->execute([$table_name, $page_limit, ($page - 1) * $page_limit]); // remplace le ? par la valeur
                            $result = $sth->fetchAll();

                            if (count($result) > 0) { ?>
                                <!-- Display component table -->
                                <div class="list-group">
                                    <?php foreach ($result as $k => $component) {
                                        $specs = json_decode($component['specifications'], true);
                                    ?>
                                        <a href="<?php echo '/view_component.php?id=' . $component['id']; ?>" class="list-group-item list-group-item-action flex-column align-items-start">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1">
                                                    <?php echo $specs['brand'] . ' ' . $specs['name']; ?>
                                                </h5>
                                                <small class="text-muted">
                                                    <?php if ($component['validated'] == 0) { ?>
                                                        <span class="badge badge-danger badge-pill" data-toggle="tooltip" data-placement="top" title="Les informations n'ont pas été vérifiées">Non validé</span>
                                                    <?php } else { ?>
                                                        <span class="badge badge-success badge-pill" data-toggle="tooltip" data-placement="top" title="Les informations de ce composants sont correctes">Validé</span>
                                                    <?php } ?>
                                                </small>
                                            </div>
                                            <p class="mb-1">
                                                <?php foreach ($GLOBALS['cols'][$component['type']] as $key => $value) {
                                                    echo '<b>' . $value['name'] . '</b> : ' . (isset($specs[$key]) ? (isset($value['values']) ? $value['values'][$specs[$key]] : $specs[$key]) : 'Inconnu') . ' ' . (isset($value['unit']) ? $value['unit'] : "") . '<br>';
                                                } ?>
                                            </p>
                                            <small class="text-muted">
                                                <?php
                                                $d = new DateTime($component['added_by']);

                                                echo 'Ajouté par ' . (isset($component['added_by']) ? $component['added_by'] : 'Anonyme') . ' le ' . $d->format('d M yy');
                                                ?>
                                            </small>
                                        </a>
                                    <?php } ?>
                                </div>
                            <?php } else { ?>
                                <p>Oops, il semblerait qu'il n'y ait rien ici :(</p>
                            <?php } ?>

                            <!-- Table pagination -->
                            <nav aria-label="..." class="mt-4">
                                <ul class="pagination justify-content-center">
                                    <?php
                                    $page_count = $content_count / $page_limit;
                                    ?>
                                    <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                                        <a class="page-link" onclick="change_page(<?php echo intval($page) - 1; ?>);" tabindex="-1">Précédent</a>
                                    </li>
                                    <?php
                                    for ($i = 1; $i < $page_count + 1; $i++) {
                                        if ($i == $page) {
                                    ?>
                                            <li class="page-item active">
                                                <a class="page-link" onclick="change_page(<?php echo $i; ?>);"><?php echo $i; ?> <span class="sr-only">(current)</span></a>
                                            </li>
                                        <?php } else { ?>
                                            <li class="page-item">
                                                <a class="page-link" onclick="change_page(<?php echo $i; ?>);"><?php echo $i; ?></a>
                                            </li>
                                    <?php }
                                    } ?>
                                    <li class="page-item <?php if ($page >= $page_count) echo 'disabled'; ?>">
                                        <a class="page-link" onclick="change_page(<?php echo intval($page) + 1; ?>);">Suivant</a>
                                    </li>
                                </ul>
                            </nav>
                        <?php } else { ?>
                            <p>Oops, il semblerait qu'il n'y ait rien ici :(</p>
                    <?php }
                    } ?>
                    <div class="tab-pane fade show active" id="v-pills-cpu" role="tabpanel" aria-labelledby="v-pills-cpu-tab">
                        <h3>Processeurs</h3>

                        <?php
                        component_table('cpu', $pdo, $page_limit, $page);
                        ?>
                    </div>

                    <div class="tab-pane fade" id="v-pills-gpu" role="tabpanel" aria-labelledby="v-pills-gpu-tab">
                        <h3>Cartes graphiques</h3>

                        <?php
                        component_table('gpu', $pdo, $page_limit, $page);
                        ?>
                    </div>

                    <div class="tab-pane fade" id="v-pills-ram" role="tabpanel" aria-labelledby="v-pills-ram-tab">
                        <h3>Mémoire vive</h3>

                        <?php
                        component_table('ram', $pdo, $page_limit, $page);
                        ?>
                    </div>

                    <div class="tab-pane fade" id="v-pills-hdd" role="tabpanel" aria-labelledby="v-pills-hdd-tab">
                        <h3>Disques dur</h3>

                        <?php
                        component_table('hdd', $pdo, $page_limit, $page);
                        ?>
                    </div>

                    <div class="tab-pane fade" id="v-pills-ssd" role="tabpanel" aria-labelledby="v-pills-ssd-tab">
                        <h3>SSD</h3>

                        <?php
                        component_table('ssd', $pdo, $page_limit, $page);
                        ?>
                    </div>

                    <div class="tab-pane fade" id="v-pills-mb" role="tabpanel" aria-labelledby="v-pills-mb-tab">
                        <h3>Cartes mère</h3>

                        <?php
                        component_table('mb', $pdo, $page_limit, $page);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // TODO: Change page without reloading
        function change_page(page) {
            let url = window.location.href;
            url = url.substr(0, url.lastIndexOf('?'))
            console.log(url);

            let new_url = url + '?page=' + page;

            window.location.href = new_url;

            window.history.replaceState('test', 'title', url + '?page=' + page);
        }
    </script>

    <?php include('includes/footer.php'); ?>

</body>

</html>