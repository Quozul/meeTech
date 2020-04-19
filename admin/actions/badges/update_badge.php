<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

// delete empty values
foreach ($_POST as $key => $value)
    if (!empty($value)) {

        $sth = $pdo->prepare('UPDATE badge SET ' . $key . '=? WHERE name=?');

        $sth->execute([
            htmlspecialchars($_POST[$key])
        ]);
    }
