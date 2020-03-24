<div class="modal fade" id="add-component-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un composant</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

                // Get types
                $req = $pdo->prepare('SELECT id_t, name FROM component_type');
                $req->execute();
                $types = $req->fetchAll();
                ?>

                <?php if (isset($_SESSION['userid'])) { ?>
                    <form id="new-component-form" autocomplete="off">
                        <div class="form-group">
                            <label>Fabricant</label>
                            <input type="text" class="form-control" placeholder="Fabricant" name="brand" required>

                            <div class="invalid-feedback">
                                Veuillez indiquer une marque.
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Nom/modèle</label>
                            <input type="text" class="form-control" placeholder="Nom/modèle" name="name" required>

                            <div class="invalid-feedback">
                                Veuillez indiquer un nom.
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Sources</label>
                            <textarea class="form-control" name="sources" placeholder="Indiquez les sources de vos informations dans ce champ"></textarea>
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <label class="input-group-text">Type</label>
                            </div>

                            <select class="custom-select" id="component-type" name="type" required>
                                <option value="" selected>Selectionnez...</option>
                                <?php foreach ($types as $key => $type) { ?>
                                    <option value="<?php echo $type['id_t']; ?>"><?php echo $type['name']; ?></option>
                                <?php } ?>
                            </select>

                            <div class="invalid-feedback">
                                Veuillez selectionner un type de composant.
                            </div>
                        </div>

                        <div id="submit-components-group">
                            <!-- Generating form -->
                            <?php foreach ($types as $key => $type) {
                                // Get specifications for each types
                                $req = $pdo->prepare('SELECT id_s, name, unit, data_type FROM specification_list WHERE type = ?');
                                $req->execute([$type['id_t']]);
                                $specs = $req->fetchAll(); ?>

                                <div class="form-group d-none" id="submit-component-<?php echo $type['id_t']; ?>">
                                    <?php foreach ($specs as $key => $spec) { ?>
                                        <div class="input-group mb-3">

                                            <?php if ($spec['data_type'] == 'list') {
                                                // Get options
                                                $req = $pdo->prepare('SELECT id_o, option FROM specification_option WHERE specification = ?');
                                                $req->execute([$spec['id_s']]);
                                                $options = $req->fetchAll(); ?>
                                                <select class="form-control" name="<?php echo $spec['id_s']; ?>">
                                                    <option value="" selected>Selectionnez...</option>
                                                    <?php foreach ($options as $key => $option) { ?>
                                                        <option value="<?php echo $option['id_o']; ?>"><?php echo $option['option']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } else { ?>
                                                <input type="<?php echo $spec['data_type']; ?>" class="form-control" placeholder="<?php echo $spec['name']; ?>" name="<?php echo $spec['id_s']; ?>">
                                            <?php } ?>

                                            <?php if (!empty($spec['unit'])) { ?>
                                                <div class="input-group-append">
                                                    <label class="input-group-text"><?php echo $spec['unit']; ?></label>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </form>
                <?php } else { ?>
                    <div class="alert alert-warning" role="alert">
                        Vous devez être connecté pour ajouter un composant !
                    </div>
                <?php } ?>
            </div>

            <div class="modal-footer">
                <?php if (isset($_SESSION['userid'])) { ?>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-add-component-modal">Annuler</button>
                    <button type="button" class="btn btn-primary" onclick="add_component();">Ajouter</button>
                <?php } else { ?>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-add-component-modal">Fermer</button>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Hides all forms and display the one wanted
    function display_form(f, n) {
        for (const key in f)
            if (f.hasOwnProperty(key))
                f[key].classList.add('d-none');

        if (n != '')
            document.getElementById(`submit-component-${n}`).classList.remove('d-none');
    }

    // Fires display_form function when type changed and empty old infos
    document.getElementById('component-type').onchange = function() {
        let group = document.getElementById('submit-components-group');

        let children = group.children;

        let inputs = group.getElementsByTagName('input');

        // Empty all inputs
        for (const key in inputs)
            if (inputs.hasOwnProperty(key))
                inputs[key].value = "";

        display_form(children, this.value);
    };

    let add_component = function() {
        request('/actions/hardware/add_component.php', formToQuery('new-component-form')).then((res) => {
            // Call page's add component function if defined, just close modal
            if (page_add_component != undefined) page_add_component();

            if (res.response.includes('required_fields')) {
                // alert('Des champs requis ne sont pas complétés.');
                JSON.parse(res.response.split(';')[1]).forEach(name => {
                    const element = document.getElementsByName(name)[0];
                    element.classList.add('is-invalid');
                    element.addEventListener('change', function() {
                        this.classList.remove('is-invalid');
                        this.removeEventListener('change');
                    });
                });
            } else if (res.response.includes('already_exists'))
                alert('Un composant avec ce nom existe déjà.');
            else
                document.getElementById('close-add-component-modal').click();
        }).catch((e) => {
            console.log(e.response);
        });
    }
</script>