<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config.php') ;

// verify if user is connected
if (!isset($_SESSION['userid'])) {
    http_response_code(401) ;
    exit() ;
}

if (!isset($_GET['post']) || empty($_GET['post'])) {
  http_response_code(400) ;
  exit() ;
}

$id = htmlspecialchars($_GET['post']) ;

//If title or content or language isn't set
if (!isset($_POST['title'])
    || empty(trim($_POST['title']))
    || !isset($_POST['content'])
    || empty(trim($_POST['content']))
    || !isset($_POST['language'])
    || empty(trim($_POST['language']))) {
  header('location: /article/?post=' . $id . '&error=notset') ;
  exit() ;
}

$title = htmlspecialchars(trim($_POST['title'])) ;
$content = htmlspecialchars(trim($_POST['content'])) ;
$language = htmlspecialchars(trim($_POST['language'])) ;

$q = $pdo->prepare('UPDATE message SET title = ?, content = ?, default_language = ? WHERE id_m = ?') ;
$q->execute([$title, $content, $language, $id]) ;

header('location: /article/?post=' . $id . '&success=edit') ;
exit() ;
?>
