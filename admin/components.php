<?php
include('../config.php');
$page_limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;

$page_name = 'Back-office : composants';

// Columns names and description
$GLOBALS['cols'] = json_decode(file_get_contents('../includes/hardware/specifications.json'), true);
?>

<!DOCTYPE html>
<html>
<?php include('../includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">
    <?php include('../includes/header.php'); ?>

    <main role="main" class="container">
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
                            <?php include('../includes/hardware/component_form.php'); ?>
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
                    <a class="nav-link cursor-pointer <?php if ($_GET['tab'] == 'cpu') echo 'active'; ?>" data-toggle="pill" onclick="change_tab('cpu')" role="tab" aria-selected="true">Processeurs</a>
                    <a class="nav-link cursor-pointer <?php if ($_GET['tab'] == 'gpu') echo 'active'; ?>" data-toggle="pill" onclick="change_tab('gpu')" role="tab" aria-selected="false">Cartes graphiques</a>
                    <a class="nav-link cursor-pointer <?php if ($_GET['tab'] == 'ram') echo 'active'; ?>" data-toggle="pill" onclick="change_tab('ram')" role="tab" aria-selected="false">Mémoire vive</a>
                    <a class="nav-link cursor-pointer <?php if ($_GET['tab'] == 'hdd') echo 'active'; ?>" data-toggle="pill" onclick="change_tab('hdd')" role="tab" aria-selected="false">Disques durs</a>
                    <a class="nav-link cursor-pointer <?php if ($_GET['tab'] == 'ssd') echo 'active'; ?>" data-toggle="pill" onclick="change_tab('ssd')" role="tab" aria-selected="false">SSDs</a>
                    <a class="nav-link cursor-pointer <?php if ($_GET['tab'] == 'mb') echo 'active'; ?>" data-toggle="pill" onclick="change_tab('mb')" role="tab" aria-selected="false">Cartes mère</a>
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

                        // change the page to an existing one
                        $page_count = ceil($content_count / $page_limit);
                        if ($page > $page_count) {
                            $page = min($page, $page_count);
                    ?>

                            <script>
                                let url = new URL(window.location.href);
                                let params = new URLSearchParams(url.search.slice(1));
                                params.set('page', <?php echo $page ?>);

                                window.location.href = url.href.substr(0, url.href.lastIndexOf('?')) + '?' + params.toString();
                            </script>

                            <?php
                        }

                        if ($content_count > 0) {
                            $sth = $pdo->prepare('SELECT * FROM component WHERE type = ? LIMIT ? OFFSET ?');
                            $sth->execute([$table_name, $page_limit, ($page - 1) * $page_limit]); // remplace le ? par la valeur
                            $result = $sth->fetchAll();

                            if (count($result) > 0) { ?>
                                <table class="table mt-color-element rounded table-responsive">
                                    <thead>
                                        <tr>
                                            <th scope="col">Marque</th>
                                            <th scope="col">Nom</th>
                                            <!-- Component specification title -->
                                            <?php
                                            foreach ($GLOBALS['cols'][$table_name]['specs'] as $key => $value) { ?>
                                                <th scope="col"><?php echo $value['name']; ?></th>
                                            <?php } ?>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Display component table -->
                                        <?php foreach ($result as $k => $component) {
                                            $specs = json_decode($component['specifications'], true);
                                        ?>
                                            <tr>
                                                <th scope="col"><?php echo $specs['brand']; ?></th>
                                                <th scope="col"><?php echo $specs['name']; ?></th>

                                                <?php foreach ($GLOBALS['cols'][$table_name]['specs'] as $key => $value) { ?>
                                                    <th scope="col"><?php echo isset($specs[$key]) ? $specs[$key] : "Inconnu"; ?></th>
                                                <?php } ?>

                                                <!-- Action buttons -->
                                                <th scope="col">
                                                    <form action="/includes/hardware/validate_component.php" method="post">
                                                        <button type="submit" class="btn btn-sm btn-success <?php if ($component['validated'] == 1) echo 'disabled'; ?>" name="id" value="<?php echo $component['id']; ?>">
                                                            <?php
                                                            if ($component['validated'] == 1) echo 'Validé';
                                                            else echo 'Valider';
                                                            ?>
                                                        </button>
                                                    </form>
                                                    <form action="/admin/edit_component.php" method="post">
                                                        <button type="submit" class="btn btn-sm btn-primary" name="id" value="<?php echo $component['id']; ?>">Modifier</button>
                                                    </form>
                                                    <form action="/includes/hardware/remove_component.php" method="post">
                                                        <button type="submit" class="btn btn-sm btn-danger" name="id" value="<?php echo $component['id']; ?>">Supprimer</button>
                                                    </form>
                                                </th>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                            <?php } else { ?>
                                <p>Oops, il semblerait qu'il n'y ait rien ici :(</p>
                            <?php } ?>

                            <!-- Table pagination -->
                            <!-- TODO: Fix pagination on this page, see commit f1931dc -->
                            <nav aria-label="..." class="mt-4">
                                <ul class="pagination justify-content-center">
                                    <?php
                                    $page_count = $content_count / $page_limit;
                                    ?>
                                    <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                                        <a class="page-link" href="<?php echo '?page=' . (intval($page) - 1); ?>" tabindex="-1">Précédent</a>
                                    </li>
                                    <?php
                                    for ($i = 1; $i < $page_count + 1; $i++) {
                                        if ($i == $page) {
                                    ?>
                                            <li class="page-item active">
                                                <a class="page-link" href="<?php echo '?page=' . $i; ?>"><?php echo $i; ?> <span class="sr-only">(current)</span></a>
                                            </li>
                                        <?php } else { ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?php echo '?page=' . $i; ?>"><?php echo $i; ?></a>
                                            </li>
                                    <?php }
                                    } ?>
                                    <li class="page-item <?php if ($page >= $page_count) echo 'disabled'; ?>">
                                        <a class="page-link" href="<?php echo '?page=' . (intval($page) + 1); ?>">Suivant</a>
                                    </li>
                                </ul>
                            </nav>
                        <?php } else { ?>
                            <p>Oops, il semblerait qu'il n'y ait rien ici :(</p>
                    <?php }
                    } ?>

                    <div class="tab-pane <?php if ($_GET['tab'] == 'cpu') echo 'show active'; ?>" id="v-pills-cpu" role="tabpanel" aria-labelledby="v-pills-cpu-tab">
                        <h3>Processeurs</h3>

                        <?php
                        if ($_GET['tab'] == 'cpu')
                            component_table('cpu', $pdo, $page_limit, $page);
                        ?>
                    </div>

                    <div class="tab-pane <?php if ($_GET['tab'] == 'gpu') echo 'show active'; ?>" id="v-pills-gpu" role="tabpanel" aria-labelledby="v-pills-gpu-tab">
                        <h3>Cartes graphiques</h3>

                        <?php
                        if ($_GET['tab'] == 'gpu')
                            component_table('gpu', $pdo, $page_limit, $page);
                        ?>
                    </div>

                    <div class="tab-pane <?php if ($_GET['tab'] == 'ram') echo 'show active'; ?>" id="v-pills-ram" role="tabpanel" aria-labelledby="v-pills-ram-tab">
                        <h3>Mémoire vive</h3>

                        <?php
                        if ($_GET['tab'] == 'ram')
                            component_table('ram', $pdo, $page_limit, $page);
                        ?>
                    </div>

                    <div class="tab-pane <?php if ($_GET['tab'] == 'hdd') echo 'show active'; ?>" id="v-pills-hdd" role="tabpanel" aria-labelledby="v-pills-hdd-tab">
                        <h3>Disques dur</h3>

                        <?php
                        if ($_GET['tab'] == 'hdd')
                            component_table('hdd', $pdo, $page_limit, $page);
                        ?>
                    </div>

                    <div class="tab-pane <?php if ($_GET['tab'] == 'ssd') echo 'show active'; ?>" id="v-pills-ssd" role="tabpanel" aria-labelledby="v-pills-ssd-tab">
                        <h3>SSD</h3>

                        <?php
                        if ($_GET['tab'] == 'ssd')
                            component_table('ssd', $pdo, $page_limit, $page);
                        ?>
                    </div>

                    <div class="tab-pane <?php if ($_GET['tab'] == 'mb') echo 'show active'; ?>" id="v-pills-mb" role="tabpanel" aria-labelledby="v-pills-mb-tab">
                        <h3>Cartes mère</h3>

                        <?php
                        if ($_GET['tab'] == 'mb')
                            component_table('mb', $pdo, $page_limit, $page);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // TODO: Change page without reloading
        function reload_page(page) {
            let url = window.location.href;
            url = url.substr(0, url.lastIndexOf('?'))
            console.log(url);

            let new_url = url + '?page=' + page;

            window.location.href = new_url;

            window.history.replaceState('test', 'title', url + '?page=' + page);
        }

        function change_tab(tab) {
            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search.slice(1));
            params.set('tab', tab);

            window.location.href = url.href.substr(0, url.href.lastIndexOf('?')) + '?' + params.toString();
        }

        function change_page(page) {
            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search.slice(1));
            params.set('page', page);

            window.location.href = url.href.substr(0, url.href.lastIndexOf('?')) + '?' + params.toString();
        }
    </script>

    <?php include('../includes/footer.php'); ?>
</body>

</html>