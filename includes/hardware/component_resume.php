<?php include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$req = $pdo->prepare('SELECT brand, name, score FROM component WHERE id_c = ?');
$req->execute([$_GET['id']]);
$component = $req->fetch();

$req = $pdo->prepare('SELECT specification, value FROM specification WHERE component = ?');
$req->execute([$_GET['id']]);
$specs = $req->fetchAll(); ?>

<h5><?php echo $component['brand'] . ' ' . $component['name']; ?></h5>
<span>Score : <?php echo $component['score']; ?></span>

<hr>

<h5>Caractéristiques</h5>

<?php foreach ($specs as $key => $spec) {
    $req = $pdo->prepare('SELECT name, unit FROM specification_list WHERE id_s = ?');
    $req->execute([$spec['specification']]);
    $spec_info = $req->fetch(); ?>

    <li><?php echo $spec_info['name'] . ' : ' . $spec['value'] . ' ' . (!empty($spec_info['unit']) ? $spec_info['unit'] : ''); ?></li>
<?php } ?>