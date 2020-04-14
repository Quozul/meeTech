<?php include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$req = $pdo->prepare('SELECT brand, name, id_c FROM component WHERE type = ? ORDER BY brand, name');
$req->execute([$_GET['type']]);
$components = $req->fetchAll(); ?>

<option selected>Selectionnez un composant</option>

<?php foreach ($components as $key => $comp) { ?>
    <option value="<?php echo $comp['id_c']; ?>"><?php echo $comp['brand'] . ' ' . $comp['name']; ?></option>
<?php } ?>