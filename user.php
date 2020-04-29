<!DOCTYPE html>
<html lang="fr">
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'); //config.php dans le head.php
?>

<body class="d-flex vh-100 flex-column justify-content-between">

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

    <main class="container">

        <?php
        if (!isset($_GET['id'])) include('includes/nothing.php');
        else {
            $id = htmlspecialchars($_GET['id']);
            $sth = $pdo->prepare('SELECT avatar, username, email, location, prefered_language, bio, display_email FROM users WHERE id_u = ?');
            $sth->execute([$id]);
            $result = $sth->fetch(); ?>

            <?php if ($result) { ?>
                <h1>Profil de <span id="username"><?php echo $result['username']; ?></span></h1>
            <?php } else { ?>
                <h1>Utilisateur introuvable</h1>
            <?php } ?>
            <div class="jumbotron">
                <?php if (!$result) {
                    echo "<h2>Cet utilisateur n'existe pas.</h2>";
                } else { ?>

                    <div id='badge' class="float-right">
                        <div class="row">
                            <?php $sth = $pdo->prepare('SELECT img_badge, name, description FROM badge INNER JOIN badged ON badge = name WHERE user = ?');
                            $sth->execute([$id]);
                            while ($res = $sth->fetch()) { ?>
                                <img src="/images/<?php echo $res['img_badge']; ?>" alt="<?= $res['name']; ?>'s badge' " class="mt-badge col-2" style="max-width: 64px; max-height: 64px;" title="<?= $res['description']; ?>">
                            <?php } ?>
                        </div>
                        <?php if ($admin) { ?>
                            <select class="custom-select col-md-3" id="badge_name" name="badge">
                                <option>Sélectionnez un badge</option>
                                <?php
                                $q = $pdo->prepare('SELECT name FROM badge');
                                $q->execute();
                                $badge = $q->fetchAll();
                                var_dump($badge);
                                foreach ($badge as $option) {
                                ?>
                                    <option value="<?= $option['name']; ?>">
                                        <?= $option['name']; ?>
                                    </option>
                                <?php
                                }
                                ?>
                            </select>
                            <button class="btn btn-primary" type="button" onclick="add_badge()">Ajouter le badge</button>
                            <button class="btn btn-danger" type="button" onclick="supr_badge()">Supprimmer le badge</button>
                            <div class="alert" id="success_div"></div>
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
                <?php }
                if (isset($_SESSION['userid'])) { ?>
                    <div class="float-right">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createChanModal">Envoyer un message</button>
                    </div>
                <?php
                }
                include('includes/profile/mp.php'); ?>

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
        <?php } ?>
    </main>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

    <script src="/scripts/chat.js"></script>
    <script src="/scripts/main.js"></script>
    <script>
        function add_badge() {
            const id = <?= $_GET['id']; ?>;
            const badge = document.getElementById('badge_name').value;
            let request = new XMLHttpRequest;
            request.open('GET', '../actions/profile/add_badge_user/?id=' + id + '&badge=' + badge);
            request.onreadystatechange = function() {
                if (request.readyState === 4) {
                    const response = parseInt(request.responseText);
                    if (response === 1) {
                        success_div.innerHTML = "Badge ajouté";
                        success_div.className = "alert alert-success mt-3";
                        location.reload();
                    } else {
                        if (response === -2) success_div.innerHTML = "L'utilisateur posséde déjà ce badge";
                        else success_div.innerHTML = "Une erreur est survenue";
                        success_div.className = "alert alert-danger mt-3";
                    }
                }
            }
            request.send();

        }

        function supr_badge() {
            const id = <?= $_GET['id']; ?>;
            const badge = document.getElementById('badge_name').value;
            let request = new XMLHttpRequest;
            request.open('GET', '../actions/profile/supr_badge_user/?id=' + id + '&badge=' + badge);
            request.onreadystatechange = function() {
                if (request.readyState === 4) {
                    const response = parseInt(request.responseText);
                    if (response === 1) {
                        success_div.innerHTML = "Badge supprimmé";
                        success_div.className = "alert alert-success mt-3";
                        location.reload();
                    } else {
                        if (response === -2) success_div.innerHTML = "Le badge n'a pas été supprimmé";
                        else success_div.innerHTML = "Une erreur est survenue";
                        success_div.className = "alert alert-danger mt-3";
                    }
                }
            }
            request.send();

        }
    </script>

</body>

</html>