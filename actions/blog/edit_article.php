<?php
require('/meetech/config.php') ;

// verify if user is connected
if (!isset($_SESSION['userid'])) {
    http_response_code(401);
    exit();
}

if (!isset($_POST['title']) || empty(trim($_POST['title']) || !isset($_POST['content']) || empty(trim($_POST['content'])) {
  header('location: /meetech/article/?post=' . htmlspecialchars($_GET['post']) . '&error=notset') ;
  exit() ;
}

$title = htmlspecialchars(trim($_POST['title'])) ;
$content = htmlspecialchars(trim($_POST['content'])) ;
$language = htmlspecialchars(trim($_POST['default_language'])) ;
$id = htmlspecialchars($_GET['post']) ;

$q = $pdo->prepare('UPDATE message SET title = :title AND content = :content AND default_language = lang WHERE id_m = :id') ;
$q->execute([
  'title' => $title,
  'content' => $content,
  'lang' => $language,
  'id_m' => $id
])

header('location: /meetech/article/?post=' . htmlspecialchars($_GET['post']) . '&success=edit') ;
exit() ;
?>
