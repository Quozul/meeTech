<?php
$page_limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;

$page_name = 'Back-office : composants';

// Columns names and description
$cols = json_decode(file_get_contents('../includes/hardware/specifications.json'), true);
?>

<!DOCTYPE html>
<html>
<?php include('../includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">
    <?php include('../includes/header.php'); ?>

    <main role="main" class="container-fluid">
        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#exampleModalCenter">Proposer un composant</button>
        <a class="btn btn-warning float-right mr-1" href="/actions/hardware/update_component_scores.php">Mettre à jour les scores</a>

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
                        <form id="submit-component" method="post" action="/actions/hardware/add_component/" autocomplete="off">
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
                <div class="nav flex-column nav-pills bg-light rounded" role="tablist" aria-orientation="vertical">
                    <?php
                    if (!isset($_GET['tab'])) $_GET['tab'] = 'cpu';
                    foreach ($cols as $key => $value) { ?>
                        <a class="nav-link <?php if ($_GET['tab'] == $key) echo 'active';
                                            else echo 'bg-light'; ?>" data-toggle="pill" onclick="change_tab('<?php echo $key; ?>')" href="" role="tab" aria-selected="true">
                            <?php echo $value['name']; ?>
                            <span class="badge badge-light float-right">
                                <?php
                                $sth = $pdo->prepare('SELECT COUNT(*) FROM component WHERE type = ?');
                                $sth->execute([$key]);
                                $content_count = $sth->fetch()[0];
                                echo $content_count;
                                ?>
                            </span>
                        </a>
                    <?php } ?>
                </div>
            </div>

            <div class="col-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane show active" role="tabpanel">
                        <?php if (isset($_GET['tab']) && isset($cols[$_GET['tab']])) { ?>
                            <h3><?php echo $cols[$_GET['tab']]['name']; ?></h3>

                            <?php
                            $sth = $pdo->prepare('SELECT COUNT(*) FROM component WHERE type = ?');
                            $sth->execute([$_GET['tab']]);
                            $content_count = $sth->fetch()[0];

                            if ($content_count > 0) {
                                // change the page to an existing one
                                $page_count = ceil($content_count / $page_limit);
                                if (1 > $page || $page > $page_count) {
                                    $page = min(max($page, 1), $page_count);;
                                    echo $page;
                            ?>

                                    <script>
                                        let url = new URL(window.location.href);
                                        let params = new URLSearchParams(url.search.slice(1));
                                        params.set('page', <?php echo $page ?>);

                                        window.location.href = url.href.substr(0, url.href.lastIndexOf('?')) + '?' + params.toString();
                                    </script>

                                <?php
                                }

                                $sth = $pdo->prepare('SELECT * FROM component WHERE type = ? LIMIT ? OFFSET ?');
                                $sth->execute([$_GET['tab'], $page_limit, ($page - 1) * $page_limit]); // remplace le ? par la valeur
                                $result = $sth->fetchAll();

                                if (count($result) > 0) { ?>
                                    <table class="table mt-color-element rounded">
                                        <thead>
                                            <tr>
                                                <th scope="col">Marque</th>
                                                <th scope="col">Nom</th>
                                                <!-- Component specification title -->
                                                <?php
                                                foreach ($cols[$_GET['tab']]['specs'] as $key => $value) { ?>
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
                                                    <th scope="col"><?php echo $component['brand']; ?></th>
                                                    <th scope="col"><?php echo $component['name']; ?></th>

                                                    <?php foreach ($cols[$_GET['tab']]['specs'] as $key => $value) { ?>
                                                        <th scope="col"><?php echo isset($specs[$key]) ? $specs[$key] : "Inconnu"; ?></th>
                                                    <?php } ?>

                                                    <!-- Action buttons -->
                                                    <th scope="col">
                                                        <form action="/actions/hardware/validate_component.php" method="post">
                                                            <button type="submit" class="btn btn-sm btn-success w-100 mb-1 <?php if ($component['validated'] == 1) echo 'disabled'; ?>" name="id" value="<?php echo $component['id']; ?>">
                                                                <?php
                                                                if ($component['validated'] == 1) echo 'Validé';
                                                                else echo 'Valider';
                                                                ?>
                                                            </button>
                                                        </form>
                                                        <form action="/edit_component.php" method="post">
                                                            <button type="submit" class="btn btn-sm btn-primary w-100 mb-1" name="id" value="<?php echo $component['id']; ?>">Modifier</button>
                                                        </form>
                                                        <a class="btn btn-sm btn-danger w-100 mb-1" onclick="delete_component(<?php echo $component['id']; ?>)">Supprimer</a>
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
                            <?php } ?>
                        <?php } ?>
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

        function delete_component(id) {
            request('/actions/hardware/remove_component.php', `id=${id}`)
                .then(() => {
                    alert('Composant supprimé avec succès!');
                });
        }
    </script>

    <?php include('../includes/footer.php'); ?>
</body>

</html>