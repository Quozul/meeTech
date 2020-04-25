<?php

// attribution chan auto --> chan
$sth = $pdo->prepare('INSERT INTO channel (name) VALUES(?)');
$sth->execute($_POST['name']);
// avec qui discuter --> recip


// creer le salon
