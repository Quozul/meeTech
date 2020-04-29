<!DOCTYPE html>
<html lang="fr">
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

    <main class="container">
        <?php
        include($_SERVER['DOCUMENT_ROOT'] . '/includes/hardware/new_component_modal.php');

        // Fetch component types
        $req = $pdo->prepare('SELECT name, id_t FROM component_type');
        $req->execute();
        $types = $req->fetchAll(); ?>

        <button class="btn btn-success float-right" data-toggle="modal" data-target="#add-component-modal">Ajouter un composant</button>
        <h1>Composants</h1>

        <div class="row">
            <div class="col-3">
                <div class="nav flex-column nav-pills bg-light rounded" role="tablist" aria-orientation="vertical">
                    <div class="nav-link tab-selector">
                        <h5>Tri</h5>
                        <hr>

                        <form class="mb-3">
                            <label>Limite de page</label>
                            <select class="form-control" id="page-limit">
                                <option value="5" selected>5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="30">30</option>
                                <option value="50">50</option>
                            </select>

                            <label>Ordre de tri</label>
                            <select class="form-control" id="page-order">
                                <option value="new" selected>Nouveaux</option>
                                <option value="popularity">Popularité</option>
                                <option value="score">Score</option>
                                <option value="last_comment">Dernier commentaire</option>
                                <option value="most_comment">Nb. commentaires</option>
                            </select>
                        </form>

                        <!-- <h5>Filtrage</h5>
                        <hr>

                        <?php foreach ($types as $key => $type) {
                            // Fetch specifications for type
                            $req = $pdo->prepare('SELECT id_s, name, unit FROM specification_list WHERE type = ?');
                            $req->execute([$type['id_t']]);
                            $specs = $req->fetchAll();
                        ?>
                            <form id="<?php echo $type['id_t']; ?>-filter" class="filter">
                                <?php foreach ($specs as $key => $spec) {
                                    // Get min/max values
                                    $req = $pdo->prepare('SELECT MIN(value) FROM specification WHERE specification = ?');
                                    $req->execute([$spec['id_s']]);
                                    $min = $req->fetch()[0];

                                    $req = $pdo->prepare('SELECT MAX(value) FROM specification WHERE specification = ?');
                                    $req->execute([$spec['id_s']]);
                                    $max = $req->fetch()[0];
                                ?>
                                    <label><?php echo $spec['name']; ?></label>
                                    <input type="range" min="<?php echo $min ?? 0; ?>" min="<?php echo $max ?? 0; ?>" class="form-control-range">
                                <?php } ?>
                            </form>
                        <?php } ?> -->

                    </div>
                </div>
            </div>

            <div class="col-9">
                <ul class="nav nav-tabs border-0 flex-nowrap" id="tab-selector">
                    <?php foreach ($types as $key => $type) {
                        $req = $pdo->prepare('SELECT COUNT(*) FROM component WHERE type = ?');
                        $req->execute([$type['id_t']]);
                        $count = $req->fetch()[0]; ?>

                        <li class="nav-item">
                            <span class="nav-link" id="tab-<?php echo $type['id_t']; ?>" onclick="change_tab(<?php echo $type['id_t']; ?>);">
                                <?php echo $type['name']; ?>
                                <span class="badge badge-secondary"><?php echo $count; ?></span>
                            </span>
                        </li>
                    <?php } ?>
                </ul>

                <div id="list"></div>
            </div>
        </div>

        <script>
            const tabs = document.getElementsByClassName('nav-link');
            const filters = document.getElementsByClassName('filter');

            const url = new URL(window.location.href);
            const params = new URLSearchParams(url.search.slice(1));
            params.set('type', '<?php echo $types[0]['id_t']; ?>');
            params.set('page', 1);
            params.set('page-limit', 5);
            params.set('order', 'new');

            const update_list = page_add_component = function() {
                // Set active tab
                for (const key in tabs)
                    if (tabs.hasOwnProperty(key)) {
                        const tab = tabs[key];

                        if (tab.id == 'tab-' + params.get('type'))
                            tab.classList.add('active');
                        else
                            tab.classList.remove('active');
                    }

                // Set active filter
                for (const key in filters)
                    if (filters.hasOwnProperty(key)) {
                        const filter = filters[key];

                        if (filter.id == params.get('type') + '-filter')
                            filter.classList.remove('d-none');
                        else
                            filter.classList.add('d-none');
                    }

                getHtmlContent('/includes/hardware/component_list.php', params.toString()).then((res) => {
                    document.getElementById('list').innerHTML = res;
                }).catch((e) => {
                    console.log(e);
                });
            }

            function change_tab(t) {
                params.set('type', t);
                params.set('page', 1);
                update_list();
            }

            function change_page(p) {
                params.set('page', p);
                update_list();
            }

            document.getElementById('page-order').onchange = function() {
                params.set('order', this.value);
                update_list();
            }

            document.getElementById('page-limit').onchange = function() {
                params.set('page-limit', this.value);
                update_list();
            }

            update_list();
        </script>
    </main>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

</body>

</html>