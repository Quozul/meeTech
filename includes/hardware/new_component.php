<?php
// Columns names and description
$cols = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/includes/hardware/specifications.json'), true);
?>

<div class="form-group">
    <label for="name">Nom/modèle</label>
    <input type="text" class="form-control" id="name" placeholder="Nom/modèle" name="name">

    <label for="brand">Fabricant</label>
    <input type="text" class="form-control" id="brand" placeholder="Fabricant" name="brand">
</div>

<div class="input-group mb-3">
    <div class="input-group-prepend">
        <label class="input-group-text" for="component-type">Composant</label>
    </div>

    <select class="custom-select" id="component-type" name="type">
        <option selected>Selectionnez...</option>
        <option value="cpu">Processeur</option>
        <option value="gpu">Carte graphique</option>
        <option value="ram">Mémoire vive</option>
        <option value="hdd">Disque dur</option>
        <option value="ssd">SSD</option>
        <option value="mb">Carte mère</option>
    </select>
</div>

<span id="submit-components-group">
    <hr class="d-none" id="submit-component-delimiter">

    <?php foreach ($cols as $type => $value) { ?>
        <div class="form-group d-none" id="submit-component-<?php echo $type; ?>">
            <?php foreach ($value as $spec_name => $spec) { ?>
                <div class="input-group mb-3">
                    <?php if ($spec['input-type'] != 'select') { ?>
                        <input type="<?php echo $spec['input-type']; ?>" step="any" class="form-control" placeholder="<?php echo $spec['name']; ?>" name="<?php echo $spec_name; ?>">
                    <?php } else { ?>
                        <select class="custom-select" name="<?php echo $spec_name; ?>">
                            <option selected><?php echo $spec['name']; ?></option>
                            <?php foreach ($spec['values'] as $option_value => $option_name) { ?>
                                <option value="<?php echo $option_value; ?>"><?php echo $option_name; ?></option>
                            <?php } ?>
                        </select>
                    <?php } ?> <?php if (isset($spec['unit'])) { ?> <div class="input-group-append">
                            <span class="input-group-text"><?php echo $spec['unit']; ?></span>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</span>

<script>
    function display_form(f, n) {
        for (const key in f)
            if (f.hasOwnProperty(key))
                f[key].classList.add('d-none');

        document.getElementById(`submit-component-${n}`).classList.remove('d-none');
    }

    document.getElementById('component-type').onchange = function() {
        let group = document.getElementById('submit-components-group');

        let children = group.children;

        let inputs = group.getElementsByTagName('input');

        for (const key in inputs)
            if (inputs.hasOwnProperty(key))
                inputs[key].value = "";

        document.getElementById('submit-component-delimiter').classList.remove('d-none');

        display_form(children, this.value);
    };
</script>