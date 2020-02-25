<?php
include('../config.php');

// delete empty values
foreach ($_POST as $key => $value)
    if (!empty($value)) {

        $sth = $pdo->prepare('UPDATE users SET ' . $key . '=? WHERE id_u=?');

        $sth->execute([$_POST[$key], $_SESSION['userid']]);
    }
