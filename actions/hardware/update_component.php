<?php include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

// Verify if user is connected
if (!isset($_SESSION['userid'])) {
    echo 'not_connected';
    http_response_code(401);
    exit();
}

// Check for empty values
$empty_values = [];
if (empty($_POST['type']))
    array_push($empty_values, 'type');
if (empty($_POST['brand']))
    array_push($empty_values, 'brand');
if (empty($_POST['name']))
    array_push($empty_values, 'name');

if (count($empty_values) != 0) {
    echo $empty_values;
    echo 'required_fields';
    http_response_code(417);
    exit();
}

$comp_id = $_POST['id'];

// TODO: Check if user have permission to add validated components according to badge
$validated = 0;

// get type then removes it from post array
$type = $_POST['type'];
unset($_POST['type']);

$sources = htmlspecialchars(isset($_POST['sources']) ? $_POST['sources'] : '');
unset($_POST['sources']);

$name = htmlspecialchars($_POST['name']);
unset($_POST['name']);

$brand = htmlspecialchars($_POST['brand']);
unset($_POST['brand']);

// Update component
$req = $pdo->prepare('UPDATE component SET brand = ?, name = ?, type = ?, sources = ?, validated = FALSE WHERE id_c = ?');
$req->execute([$brand, $name, $type, $sources, $comp_id]);

// Insert component's specifications
foreach ($_POST as $key => $spec) {
    // Verify spec's type
    $req = $pdo->prepare('SELECT id_s FROM specification_list WHERE id_s = ? AND type = ?');
    $req->execute([$key, $type]);
    $res = $req->fetch();

    if ($res) {
        $req = $pdo->prepare('UPDATE specification SET value = ? WHERE specification = ? AND component = ?');
        $req->execute([htmlspecialchars($spec), $key, $comp_id]);
    }
}

include('component_score.php');
score($pdo, $type, $comp_id);
