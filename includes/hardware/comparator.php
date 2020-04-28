<?php include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$req = $pdo->prepare('SELECT name, brand, score FROM component WHERE id_c = ?');
$req->execute([$_GET['compa']]);
$comp_a = $req->fetch();

$req = $pdo->prepare('SELECT name, brand, score FROM component WHERE id_c = ?');
$req->execute([$_GET['compb']]);
$comp_b = $req->fetch();

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

<div class="w-100">
    <table class="table">
        <thead>
            <tr>
                <th>Caractéristique</th>
                <th><?= $comp_a['brand'] . ' ' . $comp_a['name']; ?></th>
                <th>Différence</th>
                <th><?= $comp_b['brand'] . ' ' . $comp_b['name']; ?></th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <?php $superior = 'equal';
                if ($comp_a['score'] > $comp_b['score'])
                    $superior = 'a';
                else if ($comp_a['score'] < $comp_b['score'])
                    $superior = 'b'; ?>
                <th>Score</th>
                <td style="background-color: <?php if ($superior == 'a') echo 'green';
                                                else if ($superior == 'b') echo 'red';
                                                else echo 'white'; ?>"><?= $comp_a['score']; ?></td>
                <td><?= $comp_b['score'] - $comp_a['score']; ?></td>
                <td style="background-color: <?php if ($superior == 'b') echo 'green';
                                                else if ($superior == 'a') echo 'red';
                                                else echo 'white'; ?>"><?= $comp_b['score']; ?></td>
            </tr>

            <?php foreach ($specs as $id => $value) {
                $superior = 'equal';
                if (isset($specs_a[$id]) && is_numeric($specs_a[$id]) && isset($specs_b[$id]) && is_numeric($specs_b[$id])) {
                    if ($specs_a[$id] > $specs_b[$id])
                        $superior = 'a';
                    else if ($specs_a[$id] < $specs_b[$id])
                        $superior = 'b';
                } ?>
                <tr>
                    <th><?php echo $value['name']; ?></th>
                    <td style="background-color: <?php if ($superior == 'a') echo 'green';
                                                    else if ($superior == 'b') echo 'red';
                                                    else echo 'white'; ?>"><?php echo isset($specs_a[$id]) ? $specs_a[$id] : 'Inconnu'; ?></td>
                    <?php if (isset($specs_a[$id]) && is_numeric($specs_a[$id]) && isset($specs_b[$id]) && is_numeric($specs_b[$id])) { ?>
                        <td><?php echo $specs_b[$id] - $specs_a[$id]; ?></td>
                    <?php } else { ?>
                        <td></td>
                    <?php } ?>
                    <td style="background-color: <?php if ($superior == 'b') echo 'green';
                                                    else if ($superior == 'a') echo 'red';
                                                    else echo 'white'; ?>"><?php echo isset($specs_b[$id]) ? $specs_b[$id] : 'Inconnu'; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>