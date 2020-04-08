<?php
require('../../config.php') ;
// verify if user is connected
if (isset($_POST['userid']) && isset($_POST['message_id'])) {
    $user = htmlspecialchars(trim($_POST['userid'])) ;
    $message = htmlspecialchars(trim($_POST['message_id'])) ;

    if (empty($user) || empty($message)) {
      echo '-2' ;
      return ;
    }

    $alreadyVoted = $pdo->prepare('SELECT user FROM vote_message WHERE user = :user AND message = :message') ;
    if ($alreadyVoted == false) {
      echo '-2' ;
      return ;
    }
    $alreadyVoted->execute([
      'user' => $user,
      'message' => $message
    ]) ;
    $userVote = $alreadyVoted->fetch() ;
    if ($userVote == true) {
      echo '-3' ;
      return ;
    }

    $mark = $pdo->prepare('INSERT INTO vote_message (user, message) VALUES (:user, :message)') ;
    if ($mark == false) {
      echo '-2' ;
      return ;
    }
    $success = $mark->execute([
      'user' => $user,
      'message' => $message
    ]) ;
    echo (int)$success ;
} else {
  echo '-1' ;
  return;
}
?>
