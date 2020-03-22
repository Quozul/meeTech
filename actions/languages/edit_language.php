<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config.php') ;


$language = strtolower(htmlspecialchars($_POST['language'])) ;
if(isset($_POST['icon'])) $icon = substr($_POST['icon'], 0, 8) . substr($_POST['icon'], 9, 8) ;
else $icon = NULL ;
$label = strtoupper(htmlspecialchars($_POST['label'])) ;


$q = $pdo->query('SELECT lang, icon, label FROM language') ;
while ($result = $q->fetch()) {
  if ($result['lang'] != $language && ($result['icon'] == $icon || $result['label'] == $label)) {
    header('location:' . $_SERVER['DOCUMENT_ROOT'] . '/admin/languages/?error=elsewhere') ;
    exit() ;
  }
}

$q = $pdo->prepare('UPDATE language SET icon = :icon, label = :label WHERE lang = :reference') ;
$q->execute([
  'icon' => $icon,
  'label' => $label,
  'reference' => $language
]) ;

header('location:' . $_SERVER['DOCUMENT_ROOT'] . '/admin/languages/') ;
exit() ;
?>
