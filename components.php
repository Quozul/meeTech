<?php
// Columns names and description
$cols = json_decode(file_get_contents('includes/hardware/specifications.json'), true);
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
                    <option value="3" selected>3</option>
                    <option value="9">9</option>
                    <option value="15">15</option>
                    <option value="30">30</option>
                    <option value="50">50</option>
                </select>
            </div>
        </form>

        <form class="float-right ml-2">
            <div class="input-group">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="page-order">Trier</label>
                </div>
                <select class="form-control" id="page-order">
                    <option value="score" selected>Score</option>
                    <option value="new">Nouveaux</option>
                    <option value="last_comment">Dernier commentaire</option>
                    <option value="most_comment">Nb. commentaires</option>
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
                        <form class="needs-validation" id="submit-component" method="post" action="/actions/hardware/add_component/" autocomplete="off" novalidate>
                            <?php include('includes/hardware/component_form.php'); ?>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="dismiss-modal" data-dismiss="modal">Annuler</button>
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

        <script>
            const tabs = document.getElementsByClassName('tab-selector');

            function update_content() {
                getHtmlContent('/includes/hardware/component_list.php', params.toString()).then((res) => {
                    document.getElementById('component-list').innerHTML = res;
                }).catch((e) => {
                    console.log(e);
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
            params.set('order', 'score');

            update_content();

            // Change tab without reload
            function change_tab(t) {
                params.set('tab', t);
                params.set('page', 1);

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

            document.getElementById('page-order').onchange = function() {
                params.set('page-order', this.value);

                update_content();
            }

            // Add a component without reload
            function add_component(form_id) {
                request('/actions/hardware/add_component.php', formToQuery(form_id)).then((req) => {
                    console.log(req.response);
                    // update list when component is submitted and hide modal
                    update_content();
                    // hide modal WITHOUT JQUERY
                    document.querySelector('#dismiss-modal').click();
                }).catch((e) => {
                    console.log(e);
                    if (e.status == 401)
                        alert('Impossible d\'effectuer cette action. Vérifiez que vous êtes bien connecté.');
                    else if (e.status == 417) {
                        // server-side form validation
                        let set_valid = function() {
                            if (this.classList.contains('is-invalid')) {
                                this.classList.remove('is-invalid');
                                this.classList.add('is-valid');
                            }
                        }

                        JSON.parse(e.response).forEach(i => {
                            let e = document.querySelector(`[name=${i}]`);
                            e.classList.add('is-invalid');

                            if (e.type == 'text')
                                e.addEventListener('keypress', set_valid);
                            else
                                e.addEventListener('change', set_valid);
                        });
                    } else
                        alert('Une erreur est survenue. Contactez un administrateur avec le code d\'erreur :\n' + e.status);
                });
            }
        </script>
    </main>

    <?php include('includes/footer.php'); ?>

</body>

</html>