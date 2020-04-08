<?php
require('../../config.php') ;
// verify if variables are set
if (isset($_SESSION['userid'])) {
    $message = htmlspecialchars(trim($_POST['message_id'])) ;
    if (empty($message)) {
      echo '-2' ;
      return ;
    }

    $alreadyVoted = $pdo->prepare('SELECT user FROM vote_message WHERE user = :user AND message = :message') ;
    if ($alreadyVoted == false) {
      echo '-4' ;
      return ;
    }
    $alreadyVoted->execute([
      'user' => $_SESSION['userid'],
      'message' => $message
    ]) ;
    $userVote = $alreadyVoted->fetch() ;
    if ($userVote == true) {
      echo '-3' ;
      return ;
    }

    $mark = $pdo->prepare('INSERT INTO vote_message (user, message) VALUES (:user, :message)') ;
    if ($mark == false) {
      echo '-4' ;
      return ;
    }
    $success = $mark->execute([
      'user' => $_SESSION['userid'],
      'message' => $message
    ]) ;
    echo (int)$success ;
} else {
  echo '-1' ;
  return;
}
?>
