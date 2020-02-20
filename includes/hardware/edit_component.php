<?php
include('../../config.php');
// TODO: Check if user have permission to add validated components according to badge
$validated = 0;

// get type then removes it from post array
$type = $_POST['type'];
unset($_POST['type']);

// calculate the capacity in GB
if ($_POST['hdd-capacity'] != "")
    $_POST['hdd-capacity'] = intval($_POST['hdd-capacity']) * ($_POST['hdd-capacity-unit'] == 'TB' ? 1000 : 1);
if ($_POST['ssd-capacity'] != "")
    $_POST['ssd-capacity'] = intval($_POST['ssd-capacity']) * ($_POST['ssd-capacity-unit'] == 'TB' ? 1000 : 1);

// unset capacity unit
unset($_POST['hdd-capacity-unit']);
unset($_POST['ssd-capacity-unit']);

// unset all empty values
foreach ($_POST as $key => $value)
    if ($value === "")
        unset($_POST[$key]);

// converts the post array to json
$specs = json_encode($_POST);

try {
    $sth = $pdo->prepare('UPDATE component SET validated = ?, type = ?, specifications = ? WHERE id = ?;');
    $sth->execute([$validated, $type, $specs, $_POST['id']]);
} catch (Exception $e) {
    echo $e;
}
?>

<noscript>JavaScript needs to be enabled to go back automatically</noscript>

<a href="javascript: history.go(-1)">Back</a>
<script>
    history.back();
</script>