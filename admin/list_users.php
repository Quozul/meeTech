<!DOCTYPE html>
<html>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'); ?>

<body>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>
    <main>
        <?php $sth = $pdo->prepare('SELECT id_u,username, email, avatar, prefered_language, location, bio FROM users');
        $sth->execute([]);
        $rec = $sth->fetchAll(); ?>

        <h1>Liste des noms d'utilisateurs</h1>
        <ul>

            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">id</th>
                        <th scope="col">avatar</th>
                        <th scope="col">Pseudo</th>
                        <th scope="col">email</th>
                        <th scope="col">Pays</th>
                        <th scope="col">Langue</th>
                    </tr>
                </thead>
                <?php foreach ($rec as $key => $user) { ?>
                    <tbody>
                        <tr>
                            <th scope="row"><?php echo $user['id_u']; ?></th>
                            <td><?php echo $user['avatar']; ?></td>
                            <td><a href="https://www.meetech.ovh/user/?id=<?php echo $user['id_u']; ?>"><?php echo $user['username']; ?></a></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['location']; ?></td>
                            <td><?php echo $user['prefered_language']; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
            </table>


        </ul>


    </main>
</body>

</html>