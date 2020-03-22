<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

//  verify that request is complete
if (!isset($_POST['comment']) || empty($_POST['comment']) || !isset($_SESSION['userid']) || !isset($_POST['id']))
    exit();

// verify that component exists
$sth = $pdo->prepare('SELECT name FROM component WHERE id_c = ?');
$sth->execute([$_POST['id']]);
if (!$sth->fetch())
    exit();

$sth = $pdo->prepare('INSERT INTO component_comment (author, content, component, date_published, parent_comment) VALUES (?, ?, ?, now(), ?)');
$sth->execute([$_SESSION['userid'], htmlspecialchars($_POST['comment']), $_POST['id'], $_POST['answers']]);

// send mail to parent comment's and component's author
function send_mail($email, $subject, $msg)
{
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= "From: no-reply@meetech.ovh\r\n";

    mail($email, $subject, $msg, $headers);
}

$sth = $pdo->prepare('SELECT username FROM users WHERE id_u = ?');
$sth->execute([$_SESSION['userid']]);
$author = $sth->fetch();

$sth = $pdo->prepare('SELECT username, email FROM users WHERE id_u = (SELECT added_by FROM component WHERE id_c = ?)');
$sth->execute([$_POST['id']]);
$component_author = $sth->fetch();

$content = "Bonjour " . $component_author['username'] . ",\r\n\r\n" . $author['username'] . " a ajouté un commentaire à votre composant, allez le <a href='https://www.meetech.ovh/view_component/?id" . $_POST['id'] . "'>voir ici.</a>";

send_mail($component_author['email'], 'Un utilisateur a commenté votre composant.', $content);

if (!is_null($_POST['answers'])) {
    $sth = $pdo->prepare('SELECT username, email FROM users WHERE id_u = (SELECT author FROM component_comment WHERE id = ?)');
    $sth->execute([$_POST['answers']]);
    $comment_author = $sth->fetch();

    $content = "Bonjour " . $comment_author['username'] . ",\r\n\r\n" . $author['username'] . " a répondu à votre commentaire, allez le <a href='https://www.meetech.ovh/view_component/?id" . $_POST['id'] . "'>voir ici.</a>";

    send_mail($comment_author['email'], 'Un utilisateur a répondu à votre commentaire.', $content);
}
