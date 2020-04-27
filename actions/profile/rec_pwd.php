<?php
include('../../config.php');

$error = '';

// Email au format valide et si deja existant
if (!isset($_POST['email']) || empty($_POST['email'])) {
    $error = 'datanotset';
    header('location: ../../lost_credentials_form/?error=' . $error);
    exit() ;
} else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $error = 'invalidmail';
    header('location: ../../lost_credentials_form/?error=' . $error);
    exit() ;
} else {
    // Existe ou non dans la BBD
    $email = htmlspecialchars($_POST['email']);
    $sth = $pdo->prepare('SELECT COUNT(id_u) FROM users WHERE email = ?');
    $sth->execute([$email]);
    $rec = $sth->fetch();

    if ($rec == 0) {
      $error = 'usernotfound';
      header('location: ../../lost_credentials_form/?error=' . $error);
      exit();
    }
}

$code = random_int(100000, 999999);

$sth = $pdo->prepare('UPDATE users SET code = ? WHERE email = ?');
$sth->execute([$code, $email]);


$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type:text/html;charset=UTF-8\r\n";
$headers .= "From: no-reply@meetech.ovh\r\n";
$email = $_POST['email'];
$subject = "Récupération du mot de passe ";
$message = "Cliquez sur le lien pour valider votre compte :" . '<a href="https://www.meetech.ovh/lost_credentials_form/"></a><br>'
. "Votre code de vérification : " . $code;

mail($email, $subject, $message, $headers);
header('location: ../../lost_credentials_form/');
exit();

?>
