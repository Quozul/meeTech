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
    $date = new DateTime();
    $sth = $pdo->prepare('INSERT INTO component (validated, type, specifications) VALUES (?, ?, ?);');
    $sth->execute([$validated, $type, $specs]);
} catch (Exception $e) {
    echo $e;
}

// old stuff
/*switch ($_POST['type']) {
    case 'cpu':
        try {
            $sth = $pdo->prepare('INSERT INTO cpu (name, brand, validated, frequency, boost_frequency, cores, threads) VALUES (?, ?, ?, ?, ?, ?, ?);');
            $sth->execute([$_POST['name'], $_POST['brand'], $validated, $_POST['cpu-frequency'], $_POST['cpu-boost-frequency'], $_POST['cpu-cores'], $_POST['cpu-threads']]);
        } catch (Exception $e) {
            echo $e;
        }

        break;
    case 'gpu':
        try {
            $sth = $pdo->prepare('INSERT INTO gpu (name, brand, validated, core_frequency, memory_frequency, memory_type, memory_capacity) VALUES (?, ?, ?, ?, ?, ?, ?);');
            $sth->execute([$_POST['name'], $_POST['brand'], $validated, $_POST['gpu-frequency'], $_POST['gpu-memory-frequency'], $_POST['gpu-memory-type'], $_POST['gpu-memory-capacity']]);
        } catch (Exception $e) {
            echo $e;
        }

        break;
    case 'ram':
        try {
            $sth = $pdo->prepare('INSERT INTO memory (name, brand, validated, type, capacity, frequency) VALUES (?, ?, ?, ?, ?, ?);');
            $sth->execute([$_POST['name'], $_POST['brand'], $validated, $_POST['ram-type'], $_POST['ram-modules'] * $_POST['ram-capacity'], $_POST['ram-frequency']]);
        } catch (Exception $e) {
            echo $e;
        }

        break;
    case 'hdd':
        try {
            $sth = $pdo->prepare('INSERT INTO hdd (name, brand, validated, capacity, speed) VALUES (?, ?, ?, ?, ?);');
            $sth->execute([$_POST['name'], $_POST['brand'], $validated, $_POST['hdd-capacity'] * ($_POST['hdd-capacity-unit'] == 'TB' ? 1000 : 1), $_POST['hdd-speed']]);
        } catch (Exception $e) {
            echo $e;
        }

        break;
    case 'ssd':
        try {
            $sth = $pdo->prepare('INSERT INTO ssd (name, brand, validated, capacity, type, write_speed, read_speed) VALUES (?, ?, ?, ?, ?, ?, ?);');
            $sth->execute([$_POST['name'], $_POST['brand'], $validated, $_POST['ssd-capacity'] * ($_POST['ssd-capacity-unit'] == 'TB' ? 1000 : 1), $_POST['ssd-type'], $_POST['ssd-write-speed'], $_POST['ssd-read-speed']]);
        } catch (Exception $e) {
            echo $e;
        }

        break;
    case 'mb':
        try {
            $sth = $pdo->prepare('INSERT INTO motherboard (name, brand, validated, chipset, socket) VALUES (?, ?, ?, ?, ?);');
            $sth->execute([$_POST['name'], $_POST['brand'], $validated, $_POST['mb-chipset'], $_POST['mb-socket']]);
        } catch (Exception $e) {
            echo $e;
        }

        break;
}
*/

echo 'Done.';
