<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config.php') ;

if(!isset($_POST['name']) || empty($_POST['name'])) {
  header('location: ../../categories/?error=setcategory') ;
  exit() ;
}

$name = trim(htmlspecialchars($_POST['name'])) ;
$description = trim(htmlspecialchars($_POST['description'])) ;

$q = $pdo->query('SELECT name, description FROM category') ;
while ($result = $q->fetch()) {
  if (strtolower($result['name']) == strtolower($name) || strtolower($result['description']) == strtolower($description)) {
    header('location: ../../categories/?error=elsewhere') ;
    exit() ;
  }
}

$q = $pdo->prepare('INSERT INTO category (name, description) VALUES (:name, :description)') ;
$q->execute([
  'name' => $name,
  'description' => $description
]) ;

header('location: ../../categories/?success=add') ;
exit() ;
?>
