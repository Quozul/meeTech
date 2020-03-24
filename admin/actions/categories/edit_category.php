<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config.php') ;

$name = trim(htmlspecialchars($_POST['name'])) ;
$description = trim(htmlspecialchars($_POST['description'])) ;

$q = $pdo->query('SELECT name, description FROM category') ;
while ($result = $q->fetch()) {
  if (strtolower($result['name']) != strtolower($name) && (strtolower($result['description']) == strtolower($description))) {
    header('location: ../../categories/?error=elsewhere') ;
    exit() ;
  }
}

$q = $pdo->prepare('UPDATE category SET description = :description WHERE name = :reference') ;
$q->execute([
  'description' => $description,
  'reference' => $name
]) ;

header('location: ../../categories/?success=edit') ;
exit() ;
?>
