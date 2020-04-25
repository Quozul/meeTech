<!DOCTYPE html>
<html>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'); //config.php dans le head.php
?>

<body class="d-flex vh-100 flex-column justify-content-between">

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

    <main role="main" class="container">

        <?php
        $id = htmlspecialchars($_GET['id']);
        $sth = $pdo->prepare('SELECT avatar, username, email, location, prefered_language, bio, display_email FROM users WHERE id_u = ?');
        $sth->execute([$id]);
        $result = $sth->fetch(); ?>

        <?php if ($result) { ?>
            <h1>Profil de <?php echo $result['username']; ?></h1>
        <?php } else { ?>
            <h1>Utilisateur introuvable</h1>
        <?php } ?>
        <div class="jumbotron">
            <?php if (!$result) {
                echo "<h2>Cet utilisateur n'existe pas.</h2>";
            } else { ?>

                <div id='badge' name='badge' class="float-right">
                    <?php $sth = $pdo->prepare('SELECT img_badge, name, description FROM badge INNER JOIN badged ON badge = name WHERE user = ?');
                    $sth->execute([$id]);
                    while ($res = $sth->fetch()) { ?>
                        <img src="/images/<?php echo $res['img_badge']; ?>" alt="<?= $res['name']; ?>'s badge' " class="mt-badge col-2" style="max-width: 64px; max-height: 64px;" title="<?= $res['description']; ?>">
                    <?php } ?>
                </div>
                <div>
                    <img src="<?php echo $result['avatar'] ? '/uploads/' . $result['avatar'] : '/images/def_avatar.png'; ?>" alt="Profile picture" class="mt-avatar col-2 rounded float-left mr-3 mb-3" style="max-width:100px; max-height:100px">

                    <p><b>Langue :</b> <?php echo $result['prefered_language']; ?></p>
                    <p><b>Localisation :</b> <?php echo $result['location']; ?></p>
                    <?php if ($result['display_email']) { ?>
                        <p><b>Adresse e-mail :</b> <?php echo $result['email']; ?></p>
                    <?php } ?>
                    <p><b>Description :</b> <?php echo $result['bio']; ?></p>
                </div>
            <?php } ?>

            <div class="row mt-3">
                <div class="col-6">
                    <?php $sth = $pdo->prepare('SELECT id_c, brand, name FROM component WHERE added_by = ?');
                    $sth->execute([$_GET['id']]);
                    $components = $sth->fetchAll(); ?>
                    <h3>Composants ajoutés <span class="badge badge-secondary"><?= count($components) ?></span></h3>
                    <ul class="list-group">
                        <?php foreach ($components as $key => $value) { ?>
                            <li class="list-group-item"><a href="/view_component/?id=<?= $value['id_c'] ?>" class="stretched-link"><?= $value['brand'] . ' ' . $value['name'] ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="col-6">
                    <?php $sth = $pdo->prepare('SELECT id_m, title FROM message WHERE author = ?');
                    $sth->execute([$_GET['id']]);
                    $messages = $sth->fetchAll(); ?>
                    <h3>Messages postés <span class="badge badge-secondary"><?= count($messages); ?></span></h3>
                    <ul class="list-group">
                        <?php foreach ($messages as $key => $value) { ?>
                            <li class="list-group-item"><a href="/article/?post=<?= $value['id_m'] ?>" class="stretched-link"><?= $value['title'] ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

</body>

</html>