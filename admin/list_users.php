<!DOCTYPE html>
<html>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'); ?>

<body>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>
    <main>
        <?php $sth = $pdo->prepare('SELECT username, email, avatar, prefered_language, location, bio FROM users');
        $sth->execute([]);
        $rec = $sth->fetchAll();

        var_dump($rec); ?>
    </main>
</body>

</html>