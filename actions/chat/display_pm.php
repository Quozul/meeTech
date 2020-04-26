<?php // Récupération des 10 derniers messages
require('../../config.php');
$chan = htmlspecialchars($_GET['chan']);
$sth = $pdo->prepare('SELECT author, content, username, date_published FROM private_message 
    INNER JOIN users ON id_u = author
    WHERE channel = ?
    ORDER BY id_pm ASC LIMIT 0, 20');
$sth->execute([$chan]); ?>

<div class="float-right">
    <form method="post">
        <input type="text" name="add_user" id="add_user-<?= $chan; ?>" placeholder="Utilisateur à ajouter"><br>
        <button type="button" onclick="add_recipient(<?= $chan; ?>)">Ajouter l'utilisateur</button>
    </form>
    <div class="alert" id="add_success-<?= $chan; ?>">

    </div>
</div>
<?php // Affichage de chaque message (toutes les données sont protégées par htmlspecialchars)
while ($data = $sth->fetch()) {
?>
    <p>
        <strong>
            <?= htmlspecialchars($data['username']); ?>
        </strong>
        <?= htmlspecialchars($data['content']); ?>
        <small class="text-muted">
            <?php
            $dp = new DateTime($data['date_published']);
            echo "Publié le " . $dp->format('d/m/Y à H:i');
            ?>
        </small>
    </p>
<?php } ?>

<form method="post">
    <input type="text" name="message" id="message-<?= $chan; ?>" placeholder="Message"><br>
    <button type="button" onclick="submitMessage(<?= $chan; ?>)">Envoyer</button>
</form>