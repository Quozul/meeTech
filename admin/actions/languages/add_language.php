<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config.php') ;

if(!isset($_POST['language']) || empty($_POST['language'])) {
  header('location: ../../languages/?error=setlang') ;
  exit() ;
}

$language = trim(strtolower(htmlspecialchars($_POST['language']))) ;
$_POST['icon'] = trim($_POST['icon']) ;
if(isset($_POST['icon']) && !empty($_POST['icon']))
  $icon = substr($_POST['icon'], 0, 8) . substr($_POST['icon'], 9, 8) ;
else $icon = NULL ;
$label = trim(strtoupper(htmlspecialchars($_POST['label']))) ;

$q = $pdo->query('SELECT lang, icon, label FROM language') ;
while ($result = $q->fetch()) {
  if ($result['lang'] == $language || $result['icon'] == $icon || $result['label'] == $label) {
    header('location: ../../languages/?error=elsewhere') ;
    exit() ;
  }
}

$q = $pdo->prepare('INSERT INTO language (lang, icon, label) VALUES (:lang, :icon, :label)') ;
$q->execute([
  'lang' => $language,
  'icon' => $icon,
  'label' => $label
]) ;

header('location: ../../languages/?success=add') ;
exit() ;
?>
