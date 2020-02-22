<?php
include('config.php');
$page_limit = isset($_GET['page-limit']) ? $_GET['page-limit'] : 3;
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Columns names and description
$cols = json_decode(file_get_contents('includes/hardware/specifications.json'), true);
$GLOBALS['cols'] = $cols;
?>

<!DOCTYPE html>
<html>
<?php include('includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">
    <?php include('includes/header.php'); ?>

    <main role="main" class="container">
        <!-- Page's limit chooser -->
        <form class="float-right ml-2">
            <div class="input-group">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="component-type">A afficher</label>
                </div>
                <select class="form-control" id="page-limit">
                    <option value="3" <?php if ($page_limit == 3) echo 'selected'; ?>>3</option>
                    <option value="9" <?php if ($page_limit == 9) echo 'selected'; ?>>9</option>
                    <option value="15" <?php if ($page_limit == 15) echo 'selected'; ?>>15</option>
                    <option value="30" <?php if ($page_limit == 30) echo 'selected'; ?>>30</option>
                    <option value="50" <?php if ($page_limit == 50) echo 'selected'; ?>>50</option>
                </select>
            </div>
        </form>

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
                        <form class="needs-validation" id="submit-component" method="post" action="/includes/hardware/add_component/" autocomplete="off" novalidate>
                            <?php include('includes/hardware/component_form.php'); ?>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary" form="submit-component">Proposer le composant</button>
                    </div>
                </div>
            </div>
        </div>

        <h1>Composants</h1>
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
                <div class="tab-content">
                    <?php foreach ($cols as $component_id => $comp_infos) {
                        if ($_GET['tab'] == $component_id) {
                    ?>
                            <div class="tab-pane <?php if ($_GET['tab'] == $component_id) echo 'show active'; ?>" id="v-pills-cpu" role="tabpanel" aria-labelledby="v-pills-cpu-tab">
                                <h3><?php echo $comp_infos['name'] ?></h3>

                                <?php
                                $sth = $pdo->prepare('SELECT COUNT(*) FROM component WHERE type = ?');
                                $sth->execute([$component_id]);
                                $content_count = $sth->fetch()[0];

                                if ($content_count > 0) {
                                    // change the page to an existing one
                                    $page_count = ceil($content_count / $page_limit);
                                    if (1 > $page || $page > $page_count) {
                                        $page = min(max($page, 1), $page_count);;
                                        echo $page;
                                ?>

                                        <script>
                                            let page = <?php echo $page ?>;
                                            let url = new URL(window.location.href);
                                            let params = new URLSearchParams(url.search.slice(1));
                                            params.set('page', page);

                                            window.location.href = url.href.substr(0, url.href.lastIndexOf('?')) + '?' + params.toString();
                                        </script>

                                    <?php }

                                    $sth = $pdo->prepare('SELECT * FROM component WHERE type = ? ORDER BY score DESC LIMIT ? OFFSET ?');
                                    $sth->execute([$component_id, $page_limit, ($page - 1) * $page_limit]); // remplace le ? par la valeur
                                    $result = $sth->fetchAll();

                                    if (count($result) > 0) { ?>
                                        <!-- Display component list -->
                                        <div class="list-group">
                                            <?php foreach ($result as $k => $component) {
                                                $specs = json_decode($component['specifications'], true);
                                            ?>
                                                <a href="<?php echo '/view_component.php?id=' . $component['id']; ?>" class="list-group-item list-group-item-action flex-column align-items-start">
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <h5 class="mb-1">
                                                            <?php echo (isset($specs['brand']) ? $specs['brand'] : 'NoBrand') . ' ' . (isset($specs['name']) ? $specs['name'] : 'NoName'); ?>
                                                        </h5>
                                                        <small class="text-muted">
                                                            <?php if ($component['validated'] == 0) { ?>
                                                                <span class="badge badge-danger" data-toggle="tooltip" data-placement="top" title="Les informations n'ont pas été vérifiées">Non validé</span>
                                                            <?php } else { ?>
                                                                <span class="badge badge-success" data-toggle="tooltip" data-placement="top" title="Les informations de ce composants sont correctes">Validé</span>
                                                            <?php } ?>
                                                            <span class="badge badge-primary">Score : <?php echo $component['score']; ?></span>
                                                        </small>
                                                    </div>

                                                    <!-- Component's specification -->
                                                    <p class="mb-1">
                                                        <?php
                                                        $i = 0;
                                                        foreach ($GLOBALS['cols'][$component['type']]['specs'] as $key => $value) {
                                                            if (!isset($specs[$key])) continue;
                                                            echo '<b>' . $value['name'] . '</b> : ' . (isset($specs[$key]) ? (isset($value['values']) ? $value['values'][$specs[$key]] : $specs[$key]) : 'Inconnu') . ' ' . (isset($value['unit']) ? $value['unit'] : "") . '<br>';
                                                            if (++$i == 3) break; // limit specifications to the 3 firsts one
                                                        }
                                                        ?>
                                                    </p>

                                                    <small class="text-muted">
                                                        <?php
                                                        $d = new DateTime($component['added_date']);

                                                        $username = 'Anonyme';

                                                        if (isset($component['added_by'])) {
                                                            $sth = $pdo->prepare('SELECT username FROM users WHERE id_user = ?');
                                                            $sth->execute([$component['added_by']]); // remplace le ? par la valeur
                                                            $result = $sth->fetch();

                                                            if ($result)
                                                                $username = $result['username'];
                                                        }

                                                        // TODO: Add link to user's profile
                                                        echo 'Ajouté par ' . $username . ' le ' . $d->format('d M yy');
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
                                <?php } ?>
                            </div>
                    <?php }
                    } ?>
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

        document.getElementById('page-limit').onchange = function() {
            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search.slice(1));
            params.set('page-limit', this.value);

            window.location.href = url.href.substr(0, url.href.lastIndexOf('?')) + '?' + params.toString();
        }
    </script>

    <?php include('includes/footer.php'); ?>

</body>

</html>