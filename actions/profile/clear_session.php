<?php
include('../../config.php');

if (!empty($_SESSION['userid'])) {
    $sth = $pdo->prepare('UPDATE users SET username=?,password=?, email=?, location=?, prefered_language=?, bio=? WHERE id_u=?');
    $sth->execute(['Utilisateur supprimé', '', '', '', '', '', $_SESSION['userid']]);

    session_destroy();
}

exit();
