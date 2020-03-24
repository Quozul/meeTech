<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$name = $_POST['name'];
$cat = $_POST['category'];
$id = $_POST['id'];
$formula = $_POST['score-formula'];

unset($_POST['name']);
unset($_POST['category']);
unset($_POST['id']);
unset($_POST['score-formula']);

// Verify that type doesn't already exists
$req = $pdo->prepare('SELECT name FROM component_type WHERE name = ? AND NOT id_t = ?');
$req->execute([$name, $id]);
$res = $req->fetch();

if ($res) {
    echo 'type_already_exists';
} else {
    // Format specs into an array
    $specs = [];
    foreach ($_POST as $key => $value) {
        $n = explode('-', $key);
        $n = $n[count($n) - 1];

        if (strpos($n, '_')) {
            $m = explode('_', $n);
            $specs[$m[0]]['options'][$m[1]] = htmlspecialchars($value);
        } else
            $specs[$n][substr($key, 5, 4)] = htmlspecialchars($value);
    }

    // TODO: TODO: Support for removing specs and options

    // Insert specs
    foreach ($specs as $key => $spec) {
        if (isset($spec['name'])) {
            // Check if spec already exists
            $req = $pdo->prepare('SELECT id_s FROM specification_list WHERE id_s = ?');
            $req->execute([$key]);
            $res = $req->fetch();

            if ($res) {
                $req = $pdo->prepare('UPDATE specification_list SET name = ?, unit = ?, data_type = ? WHERE id_s = ?');
                $req->execute([$spec['name'], isset($spec['unit']) ? $spec['unit'] : '', isset($spec['type']) ? $spec['type'] : 'number', $key]);
            } else {
                $req = $pdo->prepare('INSERT INTO specification_list (type, name, unit, data_type) VALUES (?, ?, ?, ?)');
                $req->execute([$id, $spec['name'], isset($spec['unit']) ? $spec['unit'] : '', isset($spec['type']) ? $spec['type'] : 'number']);
            }

            // Get specification's ID
            $req = $pdo->prepare('SELECT id_s FROM specification_list WHERE type = ? AND name = ?');
            $req->execute([$id, $spec['name']]);
            $spec_id = $req->fetch();
            $spec_id = $spec_id['id_s'];

            // Replace IDs for score formula
            $formula = str_replace('[' . $key . ']', '[' . $spec_id . ']', $formula);

            // Insert options for list specs
            if (isset($spec['options']))
                foreach ($spec['options'] as $key => $option) {
                    // Check if option already exists
                    $req = $pdo->prepare('SELECT id_o FROM specification_option WHERE id_o = ?');
                    $req->execute([$key]);
                    $res = $req->fetch();

                    if ($res) {
                        $req = $pdo->prepare('UPDATE specification_option SET specification = ?, option = ? WHERE id_o = ?');
                        $req->execute([$spec_id, $option, $key]);
                    } else {
                        $req = $pdo->prepare('INSERT INTO specification_option (specification, option) VALUES (?, ?)');
                        $req->execute([$spec_id, $option]);
                    }
                }
        }
    }

    // Update type
    $req = $pdo->prepare('UPDATE component_type SET name = ?, category = ?, score_formula = ? WHERE id_t = ?');
    $req->execute([$name, $cat, $formula, $id]);
    echo 'Updated sucessfully!';
} ?>
<script>
    window.history.back();
</script>