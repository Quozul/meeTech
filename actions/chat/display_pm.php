<?php // Récupération des messages
require('../../config.php');
$chan = htmlspecialchars($_GET['chan']);
$sth = $pdo->prepare('SELECT author, content, username, date_published FROM private_message 
    INNER JOIN users ON id_u = author
    WHERE channel = ?
    ORDER BY id_pm ASC');
$sth->execute([$chan]); ?>

<div class="float-right">
    <div>
        <button class="btn btn-info btn-sm ml-3" onclick="getChat(<?= $chan; ?>)">&#8634</button>
    </div>
    <hr>
    <form method="post" class="mb-3 ml-3">
        <input class="form-control mb-2" type="text" name="add_user" id="add_user-<?= $chan; ?>" placeholder="Utilisateur à ajouter">
        <button type="button" class="btn btn-success btn-sm" onclick="add_recipient(<?= $chan; ?>)">Ajouter l'utilisateur</button>
    </form>
    <small class="alert" id="add_success-<?= $chan; ?>"></small>
    <hr>
    <div>
        <button class="btn btn-danger btn-sm ml-3 mb-3" onclick="leaveChat(<?= $chan; ?>)">Quitter le salon</button>
    </div>
</div>
<div id="display_mp-<?= $chan; ?>" name="display_mp" style="max-height: 60vh; overflow: scroll; overflow-x: hidden;">
    <?php // Affichage de chaque message
    while ($data = $sth->fetch()) {
    ?>
        <p>
            <strong>
                <?= htmlspecialchars($data['username']); ?>
            </strong>
            <small class="text-muted">
                <?php
                $dp = new DateTime($data['date_published']);
                echo $dp->format('d/m/Y à H:i');
                ?>
            </small>
            <br>
            <?= htmlspecialchars($data['content']); ?>
        </p>
    <?php } ?>
</div>

<form method="post">
    <div class="input-group mb-2">
        <textarea class="form-control" type="text" name="message" id="message-<?= $chan; ?>" placeholder="Message"></textarea>
        <div class="input-group-append">
            <button type="button" class="btn btn-info" onclick="submitMessage(<?= $chan; ?>)">Envoyer</button>
        </div>
    </div>
</form>
<div class="alert" id="size_mp-<?= $chan; ?>" name="size_mp">

</div>