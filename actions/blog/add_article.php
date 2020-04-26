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

if (isset($_FILES['image']) && !empty($_FILES['image'])) {
  $count = $pdo->query('SELECT MAX(id_m) FROM message') ;
  $id_m = $count->fetch()[0] ;

  //Check file format
  $acceptable = [
  	'image/jpeg',
  	'image/jpg',
  	'image/gif',
  	'image/png'
  ] ;
  echo $_FILES['image']['type'] ;
  if(!in_array($_FILES['image']['type'], $acceptable)) {
  	echo 'error' ;
  	header('location: '. $_SERVER['DOCUMENT_ROOT'] . 'blog/?error=file1') ;
  	exit ;
  }

  //Check file size
  $maxsize = 1024 * 1024 ; //sets size to 1Mo
  if ($_FILES['image']['size'] > $maxsize) {
  	header('location: '. $_SERVER['DOCUMENT_ROOT'] . 'blog/?error=file2') ;
  	exit ;
  }

  //File path
  $path = '../../uploads/' ;
  if(!file_exists($path)) {
  	mkdir($path, 0777, true) ;
  }
  $temp = explode('.', $_FILES['avatar']['name']) ;
  $extension = end($temp) ;
  $name = preg_replace('# #', '_', $_FILES['image']['name']) ;
  $newname = $id_m . '_' . time() . '_' . $name '.' . $extension ;
  $path .= $newname ;

  move_uploaded_file($_FILES['image']['tmp_name'], $path) ;

  $stmt = $pdo->prepare('INSERT INTO file (added_by, message, file_name) VALUES (:user, :message, :name)') ;
  $res = $stmt->execute([
    'user' => $_SESSION['userid'],
    'message' => $id_m,
    'name' => $newname
  ]) ;
}

header('location: /' . $category . '/') ;
exit ;
?>
