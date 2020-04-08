<?php
include('../../config.php');

$user = htmlspecialchars(trim($_GET['user']));

if (!empty($user)) {
    $sth = $pdo->prepare('UPDATE users SET username=?,password=?, email=?, location=?, prefered_language=?, bio=? WHERE id_u=?');
    $sth->execute(['Utilisateur supprimé', '', '', '', '', '', $user]);
}
//header('location: /admin/list_users/');

exit();
