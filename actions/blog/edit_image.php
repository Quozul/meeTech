<?php
require('../../config.php') ;
$id_m = htmlspecialchars($_GET['post']) ;

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
header('location: /article/?post=' . $id_m) ;
exit() ;
?>
