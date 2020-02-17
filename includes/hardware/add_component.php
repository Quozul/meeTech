<?php
include('../../config.php');
// Check if user have permission to add validated components
$validated = 0;

switch ($_POST['type']) {
    case 'cpu':
        try {
            $sth = $pdo->prepare('INSERT INTO cpu (name, brand, validated, frequency, cores, threads) VALUES (?, ?, ?, ?, ?, ?);');
            $sth->execute([$_POST['name'], $_POST['brand'], $validated, $_POST['cpu-frequency'], $_POST['cpu-cores'], $_POST['cpu-threads']]);
        } catch (Exception $e) {
            echo $e;
        }

        break;
}

echo 'Done.';
