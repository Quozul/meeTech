<?php function form($pdo, $comp_id)
{
    // Get types
    $req = $pdo->prepare('SELECT id_t, name FROM component_type');
    $req->execute();
    $types = $req->fetchAll();

    // Fetch component
    $req = $pdo->prepare('SELECT name, brand, type, sources, added_by, added_date FROM component WHERE id_c = ?');
    $req->execute([$comp_id]);
    $component = $req->fetch();
?>

    <?php if (isset($_SESSION['userid'])) { ?>
        <form id="edit-component-form" autocomplete="off">
            <div class="form-group">
                <label>Fabricant</label>
                <input type="text" class="form-control" placeholder="Fabricant" name="brand" value="<?php echo $component['brand']; ?>" required>

                <div class="invalid-feedback">
                    Veuillez indiquer une marque.
                </div>
            </div>

            <div class="form-group">
                <label>Nom/modèle</label>
                <input type="text" class="form-control" placeholder="Nom/modèle" name="name" value="<?php echo $component['name']; ?>" required>

                <div class="invalid-feedback">
                    Veuillez indiquer un nom.
                </div>
            </div>

            <div class="form-group">
                <label>Sources</label>
                <textarea class="form-control" name="sources" placeholder="Indiquez les sources de vos informations dans ce champ"><?php echo $component['sources']; ?></textarea>
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <label class="input-group-text">Type</label>
                </div>

                <select class="custom-select" id="component-type" name="type" required>
                    <option value="" selected>Selectionnez...</option>
                    <?php foreach ($types as $key => $type) { ?>
                        <option value="<?php echo $type['id_t']; ?>" <?php echo $type['id_t'] == $component['type'] ? 'selected' : '' ?>><?php echo $type['name']; ?></option>
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
                        <?php foreach ($specs as $key => $spec) {
                            $req = $pdo->prepare('SELECT value FROM specification WHERE component = ? AND specification = ?');
                            $req->execute([$comp_id, $spec['id_s']]);
                            $value = $req->fetch(); ?>

                            <div class="input-group mb-3">

                                <?php if ($spec['data_type'] == 'list') {
                                    // Get options
                                    $req = $pdo->prepare('SELECT id_o, option FROM specification_option WHERE specification = ?');
                                    $req->execute([$spec['id_s']]);
                                    $options = $req->fetchAll(); ?>
                                    <select class="form-control" name="<?php echo $spec['id_s']; ?>">
                                        <option value="" selected>Selectionnez...</option>
                                        <?php foreach ($options as $key => $option) { ?>
                                            <option value="<?php echo $option['id_o']; ?>" <?php if ($value && $value[0] == $option['id_o']) echo 'selected' ?>><?php echo $option['option']; ?></option>
                                        <?php } ?>
                                    </select>
                                <?php } else { ?>
                                    <input type="<?php echo $spec['data_type']; ?>" class="form-control" placeholder="<?php echo $spec['name']; ?>" name="<?php echo $spec['id_s']; ?>" <?php if ($value) echo 'value="' . $value[0] . '"' ?>>
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

            display_form(document.getElementById('submit-components-group').children, document.getElementById('component-type').value);

            let update_component = function() {
                const url_params = new URLSearchParams(window.location.search);
                const comp_id = url_params.get('id');
                request('/actions/hardware/update_component.php', formToQuery('edit-component-form') + 'id=' + comp_id).then((res) => {
                    // Call page's add component function if defined, just close modal
                    if (page_update_component != undefined) page_update_component();
                    console.log(res.response);
                }).catch((e) => {
                    console.log(e.response);
                });
            }
        </script>
    <?php } else { ?>
        <div class="alert alert-warning" role="alert">
            Vous devez être connecté pour modifier un composant !
        </div>
    <?php } ?>
<?php } ?>