<?php // Récupération des 10 derniers messages
require('../../config.php');
$chan = htmlspecialchars($_GET['chan']);
$sth = $pdo->prepare('SELECT author, content, username FROM private_message 
    INNER JOIN users ON id_u = author
    WHERE channel = ?
    ORDER BY id_pm ASC LIMIT 0, 20');
$sth->execute([$chan]);

// Affichage de chaque message (toutes les données sont protégées par htmlspecialchars)
while ($data = $sth->fetch()) {
?>
    <p>
        <strong>
            <?= htmlspecialchars($data['username']); ?>
        </strong>
        <?= htmlspecialchars($data['content']); ?>
    </p>
<?php } ?>

<form method="post">
    <input type="text" name="message" id="message-<?= $chan; ?>" placeholder="Message"><br>
    <button type="button" onclick="submitMessage(<?= $chan; ?>)">Envoyer</button>
</form>