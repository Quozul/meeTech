<?php
// TODO: Update this process to support the updated form
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$name = $_POST['name'];
$cat = $_POST['category'];
$formula = $_POST['score-formula'];

unset($_POST['name']);
unset($_POST['category']);
unset($_POST['score-formula']);

// Verify that type doesn't already exists
$req = $pdo->prepare('SELECT name FROM component_type WHERE name = ?');
$req->execute([$name]);
$res = $req->fetch();

if ($res) {
    echo 'type_already_exists';
} else {
    // Insert type
    $req = $pdo->prepare('INSERT INTO component_type (name, category) VALUES (?, ?)');
    $req->execute([$name, $cat]);

    $req = $pdo->prepare('SELECT id_t FROM component_type WHERE name = ?');
    $req->execute([$name]);
    $type_id = $req->fetch()[0];

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

    // Insert specs
    foreach ($specs as $key => $spec) {
        if (isset($spec['name'])) {
            // Insert specification
            $req = $pdo->prepare('INSERT INTO specification_list (type, name, unit, data_type) VALUES (?, ?, ?, ?)');
            $req->execute([$type_id, $spec['name'], isset($spec['unit']) ? $spec['unit'] : '', isset($spec['type']) ? $spec['type'] : 'number']);

            // Get specification's ID
            $req = $pdo->prepare('SELECT id_s FROM specification_list WHERE type = ? AND name = ?');
            $req->execute([$type_id, $spec['name']]);
            $spec_id = $req->fetch()[0];

            // Replace IDs for score formula
            $formula = str_replace('[' . $key . ']', '[' . $spec_id . ']', $formula);

            // Insert options for list specs
            if (isset($spec['options']))
                foreach ($spec['options'] as $key => $option) {
                    $req = $pdo->prepare('INSERT INTO specification_option (specification, option) VALUES (?, ?)');
                    $req->execute([$spec_id, $option]);
                }
        }
    }

    $req = $pdo->prepare('UPDATE component_type SET score_formula = ? WHERE id_t = ?');
    $req->execute([$formula, $type_id]);
} ?>
<script>
    window.history.back();
</script>