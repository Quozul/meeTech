<?php include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$req = $pdo->prepare('SELECT id_s, name, unit FROM specification_list WHERE type = ?');
$req->execute([$_GET['type']]);

$specs = array();
while ($row = $req->fetch())
    $specs[$row['id_s']] = ['name' => $row['name'], 'unit' => $row['unit']];

// Fetch specifications of component A
$req = $pdo->prepare('SELECT specification, value FROM specification WHERE component = ?');
$req->execute([$_GET['compa']]);

$specs_a = array();
while ($row = $req->fetch())
    $specs_a[$row['specification']] = $row['value'];

// Fetch specifications of component B
$req = $pdo->prepare('SELECT specification, value FROM specification WHERE component = ?');
$req->execute([$_GET['compb']]);

$specs_b = array();
while ($row = $req->fetch())
    $specs_b[$row['specification']] = $row['value'];

?>

<div>
    <table class="table table-responsive w-100">
        <thead>
            <tr>
                <th>Caractéristique</th>
                <th>Valeur composant A</th>
                <th>Différence</th>
                <th>Valeur composant B</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($specs as $id => $value) { ?>
                <tr>
                    <th><?php echo $value['name']; ?></th>
                    <th><?php echo isset($specs_a[$id]) ? $specs_a[$id] : 'Inconnu'; ?></th>
                    <?php if (isset($specs_a[$id]) && is_numeric($specs_a[$id]) && isset($specs_b[$id]) && is_numeric($specs_b[$id])) { ?>
                        <th><?php echo $specs_b[$id] - $specs_a[$id]; ?></th>
                    <?php } else { ?>
                        <th></th>
                    <?php } ?>
                    <th><?php echo isset($specs_b[$id]) ? $specs_b[$id] : 'Inconnu'; ?></th>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>