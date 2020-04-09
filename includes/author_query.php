<?php
function author_query($auth_id, $pdo) {
    $author_query = $pdo->prepare('SELECT username FROM users WHERE id_u = ?') ;
    $author_query->execute([$auth_id]) ;
    $auth = $author_query->fetch()[0] ;
    return $auth ;
}
?>
