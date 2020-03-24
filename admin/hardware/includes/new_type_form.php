<form id="add-type-form" autocomplete="off" action="/admin/hardware/actions/add_type.php" method="post">
    <div class="form-group">
        <label>Nom</label>
        <input name="name" type="text" class="form-control" placeholder="Nom du type">
    </div>

    <div class="form-group">
        <label>Categorie</label>
        <select name="category" class="form-control">
            <option value="internal">Interne</option>
            <option value="external">Externe (périphérique)</option>
        </select>
    </div>

    <div class="form-group" id="spec-list">
        <label for="type-name">Caractéristiques</label>

        <div class="spec-input">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text spec-number">1</span>
                </div>

                <select name="spec-type-1" class="form-control spec-data-type">
                    <option value="number" selected>Numérique</option>
                    <option value="string">Texte</option>
                    <option value="list">Liste</option>
                </select>

                <input name="spec-name-1" type="text" placeholder="Nom" class="form-control">
                <input name="spec-unit-1" type="text" placeholder="Unité" class="form-control col-2">

                <div class="input-group-append">
                    <span class="btn input-group-text" title="Supprimer" onclick="remove_spec_field(this);">❌</span>
                </div>
            </div>
        </div>
    </div>

    <span class="btn btn-sm btn-success w-100 mb-2" onclick="add_spec_field();">Ajouter une caractéristique</span>

    <div class="form-group">
        <label>Formule du score de performance</label>
        <input name="score-formula" type="text" class="form-control" placeholder="Formule du score">
        <label class="text-muted">
            Entrez une formule mathématique et utilisez les valeurs entrées (leur type doit être numérique) en indiquant leur ID de champ entre crochet.
            Vous pouvez utiliser les <a href="https://www.php.net/manual/fr/ref.math.php">fonctions mathématiques du PHP</a> si vous le souhaitez.<br>
            Exemple pour 3 champs numériques :
            <pre>[1] * 2 + [2] / 100 + log([3] + 1)</pre>
        </label>
    </div>
</form>

<script>
    function add_spec_field() {
        const spec_list = document.getElementById('spec-list');
        const specs = spec_list.getElementsByClassName('spec-input');

        const keys = Object.keys(specs);
        const last_field = specs[keys[keys.length - 1]];

        const id = parseInt(last_field.getElementsByClassName('spec-number')[0].innerHTML) + 1;

        const spec_input = document.createElement('div');
        spec_input.classList.add('spec-input');
        spec_input.innerHTML = `<div class=" input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text spec-number">${id}</span>
                </div>

                <select name="spec-type-${id}" class="form-control spec-data-type">
                    <option value="number">Numérique</option>
                    <option value="string">Texte</option>
                    <option value="list">Liste</option>
                </select>

                <input name="spec-name-${id}" type="text" placeholder="Nom" class="form-control">
                <input name="spec-unit-${id}" type="text" placeholder="Unité" class="form-control col-2">

                <div class="input-group-append">
                    <span class="btn input-group-text" title="Supprimer" onclick="remove_spec_field(this);">❌</span>
                </div>`;

        spec_list.lastElementChild.after(spec_input);

        const selects = document.getElementsByClassName('spec-data-type');
        for (const key in selects)
            if (selects.hasOwnProperty(key))
                selects[key].onchange = function() {
                    const input_group = this.parentNode.parentNode;
                    if (this.value == 'list') {
                        const spec_values_input = document.createElement('div');
                        spec_values_input.classList.add('border-left', 'pl-3', 'pr-5', 'mb-3', 'ml-5', 'spec-values')

                        const values_input = document.createElement('div');
                        values_input.classList.add('values');

                        const add_option = document.createElement('span');
                        add_option.classList.add('btn', 'btn-sm', 'btn-success', 'w-100')
                        add_option.onclick = () => {
                            add_option_input(add_option)
                        };
                        add_option.innerHTML = 'Ajouter une option';

                        spec_values_input.appendChild(values_input);
                        spec_values_input.appendChild(add_option);

                        input_group.after(spec_values_input)
                        add_option_input(add_option)
                    } else if (input_group.nextElementSibling.classList.contains('spec-values')) input_group.nextElementSibling.remove();
                };
    }

    function add_option_input(e) {
        const values = e.parentNode.getElementsByClassName('values')[0];
        const id = parseInt(e.parentNode.previousElementSibling.getElementsByClassName('spec-number')[0].innerHTML);


        const options = values.getElementsByClassName('option-number');
        const keys = Object.keys(options);
        const last_option = options[keys[keys.length - 1]];

        let option_id = 1;
        if (last_option != undefined)
            option_id = parseInt(last_option.innerHTML) + 1;

        const list_option_input = document.createElement('div');
        list_option_input.classList.add('input-group', 'mb-1')
        list_option_input.innerHTML = `<div class="input-group-prepend">
                    <span class="input-group-text option-number">${option_id}</span>
                </div>

                <input name="spec-option-option-${id}.${option_id}" type="text" placeholder="Nom" class="form-control">

                <div class="input-group-append">
                    <span class="btn input-group-text" title="Supprimer" onclick="remove_option_field(this);">❌</span>
                </div>`;

        values.appendChild(list_option_input);
    }

    function remove_option_field(e) {
        e.parentNode.parentNode.remove();
    }

    function remove_spec_field(e) {
        const input = e.parentNode.parentNode.parentNode;
        if (document.getElementsByClassName('spec-input').length <= 1) {
            alert('Impossible de retirer le dernier champ !');
            return;
        }
        const sibling = input.nextElementSibling;
        if (sibling != null)
            if (sibling.classList.contains('spec-values')) sibling.remove();
        input.remove();
    }
</script>