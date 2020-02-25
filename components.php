<?php
include('config.php');

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

        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#add-component-modal">Proposer un composant</button>

        <!-- Add component modal/form -->
        <div class="modal fade" id="add-component-modal" tabindex="-1" role="dialog" aria-labelledby="add-component-modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajout d'un composant</h5>
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
                        <button class="btn btn-primary" onclick="add_component('submit-component');">Proposer le composant</button>
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
                    foreach ($cols as $key => $value) { ?>
                        <a class="nav-link tab-selector" data-toggle="pill" onclick="change_tab('<?php echo $key; ?>')" id="tab-<?php echo $key; ?>" href="" role="tab" aria-selected="true">
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
                <div class="tab-content" id="component-list">
                    <!-- Include component list -->
                </div>
            </div>
        </div>
    </main>

    <script>
        const tabs = document.getElementsByClassName('tab-selector');

        function update_content() {
            getHtmlContent('/includes/hardware/component_list.php', params.toString()).then((res) => {
                document.getElementById('component-list').innerHTML = res.getElementsByTagName('body')[0].innerHTML;
            });

            // removes active class from all tabs
            for (const key in tabs)
                if (tabs.hasOwnProperty(key))
                    tabs[key].classList.remove('active');

            // re-add active class to tab
            document.getElementById('tab-' + params.get('tab')).classList.add('active');
        }

        const url = new URL(window.location.href);
        const params = new URLSearchParams(url.search.slice(1));
        params.set('tab', 'cpu');
        params.set('page', 1);
        params.set('page-limit', 3);

        update_content();


        // Change tab without reload
        function change_tab(t) {
            params.set('tab', t);

            update_content();
        }

        // Change page without reload
        function change_page(p) {
            params.set('page', p);

            update_content();
        }

        // Change displayed inputs according to selected type
        document.getElementById('page-limit').onchange = function() {
            params.set('page-limit', this.value);

            update_content();
        }

        // Add a component without reload
        function add_component(form_id) {
            const form = document.getElementById(form_id);
            const form_data = new FormData(form);
            let query = '';

            for (let pair of form_data.entries())
                query += pair[0] + '=' + pair[1] + '&';

            request('/includes/hardware/add_component.php', query).then(() => {
                // update list when component is submitted and hide modal
                update_content();
                $('#add-component-modal').modal('hide')
            }).catch((e) => {
                alert('Une erreur est survenue.');
            });

        }
    </script>

    <?php include('includes/footer.php'); ?>

</body>

</html>