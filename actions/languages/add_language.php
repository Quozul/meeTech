<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config.php') ;

if(!isset($_POST['language']) || empty($_POST['language'])) {
  header('location:' . $_SERVER['DOCUMENT_ROOT'] . '/admin/languages/?error=setlang') ;
  exit() ;
}

$language = strtolower(htmlspecialchars($_POST['language'])) ;
if(isset($_POST['icon'])) $icon = substr($_POST['icon'], 0, 8) . substr($_POST['icon'], 9, 8) ;
else $icon = NULL ;
$label = strtoupper(htmlspecialchars($_POST['label'])) ;

$q = $pdo->query('SELECT lang, icon, label FROM language') ;
while ($result = $q->fetch()) {
  if ($result['lang'] == $language || $result['icon'] == $icon || $result['label'] == $label) {
    header('location:' . $_SERVER['DOCUMENT_ROOT'] . '/admin/languages/?error=elsewhere') ;
    exit() ;
  }
}

$q = $pdo->prepare('INSERT INTO language (lang, icon, label) VALUES (:lang, :icon, :label)') ;
$q->execute([
  'lang' => $language,
  'icon' => $icon,
  'label' => $label
]) ;

header('location:' . $_SERVER['DOCUMENT_ROOT'] . '/admin/languages/?success=add') ;
exit() ;
?>
