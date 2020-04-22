<?php include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$score = 0;

foreach ($_GET as $type => $component) {
    $req = $pdo->prepare('SELECT score FROM component WHERE id_c = ?');
    $req->execute([$component]);
    $score = $score + $req->fetch()[0];
}

echo $score;
