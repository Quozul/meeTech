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
$language = htmlspecialchars(trim($_POST['language'])) ;
$category = htmlspecialchars(trim($_POST['category'])) ;
$signaled = 0 ;

$q = $pdo->prepare('INSERT INTO message (author, title, content, date_published, default_language, category, signaled) VALUES (:author, :title, :content, :date_published, :language, :category, :signaled)') ;
$res = $q->execute([
	'author' => $author,
	'title' => $title,
	'content' => $content,
	'date_published' => $date_post,
	'language' => $language,
	'category' => $category,
	'signaled' => $signaled
]) ;

if ($res == 0) {
  header('location: /article/?cat=' . $category . '&error=0') ;
  exit ;
}

$count = $pdo->query('SELECT MAX(id_m) FROM message') ;
$id_m = $count->fetch()[0] ;


if (isset($_FILES['image']) && !empty($_FILES['image'])) {
  $accept = [
      'image/jpeg',
      'image/jpg',
      'image/gif',
      'image/png',
      'image/jfif'
  ];

  // verify the file type
  if (!in_array($_FILES['image']['type'], $accept)) {
    header('location: /article/?post=' . $id_m . '&error=file1') ;
    exit() ;
  }

  //Check file size
  $maxsize = 5 * 1024 * 1024 ; //sets size to 5Mo
  if ($_FILES['image']['size'] > $maxsize) {
    header('location: /article/?post=' . $id_m . '&error=file2') ;
    exit() ;
  }

  //File path
  $path = '../../uploads/' ;
  if(!file_exists($path)) {
  	mkdir($path, 0777, true) ;
  }
  $name = preg_replace('# #', '_', $_FILES['image']['name']) ;
  $newname = $id_m . '_' . time() . '_' . $name ;
  if (strlen($newname) > 64) {
    $tempo = substr($newname, 0, 60);
    $explode = explode('.', $newname);
    $newname = $tempo . '.' . end($explode) ;
  }
  $path .= $newname ;

  move_uploaded_file($_FILES['image']['tmp_name'], $path) ;

  $stmt = $pdo->prepare('INSERT INTO file (added_by, message, file_name) VALUES (:user, :message, :name)') ;
  $res = $stmt->execute([
    'user' => $_SESSION['userid'],
    'message' => $id_m,
    'name' => $newname
  ]) ;
}

/*$query->execute([$_SESSION['userid']]) ;
$nb_articles = $query->fetchAll()[0] ;
if ($nb_articles == 1) {
  $sth = $pdo->prepare('INSERT INTO badged (user, badge) VALUES (:user, :badge)') ;
  $sth->execute([$_SESSION['userid'], 'Publicateur']) ;
}
if ($nb_articles == 25) {
  $sth = $pdo->prepare('INSERT INTO badged (user, badge) VALUES (:user, :badge)') ;
  $sth->execute([$_SESSION['userid'], 'Publicateur_fou']) ;
}*/

header('location: /article/?post=' . $id_m) ;
exit ;
?>
