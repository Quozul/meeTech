<?php
require('../../config.php') ;

// verify if user is connected
if (!isset($_SESSION['userid'])) {
    http_response_code(401) ;
    exit() ;
}

if (!isset($_GET['post']) || empty($_GET['post'])) {
  http_response_code(400) ;
  exit() ;
}

$article = htmlspecialchars(trim($_GET['post'])) ;

if (!isset($_POST['title'])
    || empty(trim($_POST['title']))
    || !isset($_POST['content'])
    || empty(trim($_POST['content']))
    || !isset($_POST['language'])
    || empty(trim($_POST['language']))) {
  header('location: ' . $_SERVER['DOCUMENT_ROOT'] . '/translate/?post=' . $article . '&error=notset') ;
  exit() ;
}

$language = htmlspecialchars(trim($_POST['language'])) ;
$query = $pdo->prepare('SELECT default_language FROM message WHERE id_m = ?') ;
$query->execute([$article]) ;
$default = $query->fetch()[0] ;

if ($default == $language) {
  header('location: ' . $_SERVER['DOCUMENT_ROOT'] . '/translate/?post=' . $article . '&error=original') ;
  exit() ;
}

$query = $pdo->prepare('SELECT language FROM translation WHERE original_message = ?') ;
$query->execute([$article]) ;
$langs = $query->fetchAll() ;

foreach($langs as $lang) {
  if ($lang['language'] == $language) {
    header('location: ' . $_SERVER['DOCUMENT_ROOT'] . '/translate/?post=' . $article . '&error=exists') ;
    exit() ;
  }
}

$title = htmlspecialchars(trim($_POST['title'])) ;
$content = htmlspecialchars(trim($_POST['content'])) ;
$date = date('Y-m-d H:i:s') ;

$stmt = $pdo->prepare('INSERT INTO translation (language, original_message, title, content, translator, date_translated) VALUES (:lang, :id, :title, :content, :user, :date_trans)') ;
$result = $stmt->execute([
  'lang' => $language,
  'id' => $article,
  'title' => $title,
  'content' => $content,
  'user' => $_SESSION['userid'],
  'date_trans' => $date
]);

header('location: ' . $_SERVER['DOCUMENT_ROOT'] . '/article/?post=' . $article) ;
exit() ;
?>
