<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config.php') ;

// verify if user is connected
if (!isset($_SESSION['userid'])) {
    http_response_code(401);
    exit();
}

$empty_values = [];
if (empty($_POST['title']))
    array_push($empty_values, 'title');
if (empty($_POST['content']))
    array_push($empty_values, 'content');
if (empty($_POST['language']))
    array_push($empty_values, 'language');

if (count($empty_values) != 0) {
    echo json_encode($empty_values);
    http_response_code(417);
    exit();
}

$author = $_SESSION['userid'] ;
$title = htmlspecialchars($_POST['title']) ;
$content = htmlspecialchars($_POST['content']) ;
$date_post = date('Y-m-d H:i:s') ;
$language = $_POST['language'] ;
$category = $_POST['category'] ;
$signaled = 0 ;

$q = $pdo->prepare('INSERT INTO message (author, title, content, date_published, default_language, category, signaled) VALUES (:author, :title, :content, :date_published, :language, :category, :signaled)') ;
$q->execute([
	'author' => $author,
	'title' => $title,
	'content' => $content,
	'date_published' => $date_post,
	'language' => $language,
	'category' => $category,
	'signaled' => $signaled
]) ;

header('location: /' . $category . '/') ;
exit ;
?>
