<?php
function getRelatedComments ($parent_comm, $id_m, $pdo, $indent) {
    $q = $pdo->prepare('SELECT COUNT(id_c) FROM comment WHERE parent_message = ? AND parent_comment = ?') ;
    $q->execute([$id_m, $parent_comm]) ;
    $total = $q->fetch()[0] ;
    if ($total == NULL) exit() ;

    $q = $pdo->prepare('SELECT id_c, author, parent_message, content, date_published, date_edited, note FROM comment WHERE parent_message = ? AND parent_comment = ?') ;
    $q->execute([$id_m, $parent_comm]) ;
    $comments = $q->fetchAll() ;
    for ($k = 0 ; $k < $total ; ++$k) {
        $author_query = $pdo->prepare('SELECT username FROM users WHERE id_u = ?') ;
        $author_query->execute([$comments[$k]['author']]) ;
        $author = $author_query->fetch()[0] ;
        displayComment($k, $indent, $comments[$k], $pdo) ;
    }
}
?>
