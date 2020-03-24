<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

// Verify that type doesn't already exists
$req = $pdo->prepare('DELETE FROM component_type WHERE id_t = ?');
$req->execute([$_POST['id']]);

// Delete specification options
$req = $pdo->prepare('SELECT id_s FROM specification_list WHERE type = ? AND data_type = \'list\'');
$req->execute([$_POST['id']]);
$list_specs = $req->fetchAll();

foreach ($list_specs as $key => $spec) {
    $req = $pdo->prepare('DELETE FROM specification_option WHERE specification = ?');
    $req->execute([$spec['id_s']]);
}

// Delete specifications for that component
$req = $pdo->prepare('DELETE FROM specification_list WHERE type = ?');
$req->execute([$_POST['id']]); ?>
<script>
    window.history.back();
</script>