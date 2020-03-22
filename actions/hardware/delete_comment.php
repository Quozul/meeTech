<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

// Check if comment have childrens
$req = $pdo->prepare('SELECT id_c, parent_comment FROM component_comment WHERE parent_comment = ?');
$req->execute([$_POST['id']]);
$comments = $req->fetchAll();

if (empty($comments)) {
    // Delete specifications for that component
    $req = $pdo->prepare('DELETE FROM component_comment WHERE id_c = ? AND author = ?');
    $req->execute([$_POST['id'], $_SESSION['userid']]);

    // TODO: Delete parent if there isn't any children any more
} else {
    $req = $pdo->prepare('UPDATE component_comment SET author = 0, content = \'[Supprimé]\' WHERE id_c = ? AND author = ?');
    $req->execute([$_POST['id'], $_SESSION['userid']]);
}
