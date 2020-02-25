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
                    if (!isset($_GET['tab'])) $_GET['tab'] = 'cpu';
                    foreach ($cols as $key => $value) { ?>
                        <a class="nav-link <?php
                                            if ($_GET['tab'] == $key) echo 'active';
                                            else echo 'bg-light';
                                            ?>" data-toggle="pill" onclick="change_tab('<?php echo $key; ?>')" href="" role="tab" aria-selected="true">
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
                    <?php include('includes/hardware/component_list.php'); ?>
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

        function add_component(form_id) {
            const form = document.getElementById(form_id);
            const form_data = new FormData(form);
            let query = '';
            for (let pair of form_data.entries())
                query += pair[0] + '=' + pair[1] + '&';

            request('/includes/hardware/add_component.php', query).then(() => {
                alert('Composant ajouté!');
            });
        }
    </script>

    <?php include('includes/footer.php'); ?>

</body>

</html>