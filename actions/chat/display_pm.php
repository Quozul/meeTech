<?php // Récupération des messages
require('../../config.php');
$chan = htmlspecialchars($_GET['chan']);
$sth = $pdo->prepare('SELECT author, content, username, date_published FROM private_message
    INNER JOIN users ON id_u = author
    WHERE channel = ?
    ORDER BY id_pm ASC');
$sth->execute([$chan]);

// Affichage de chaque message
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
        <span class="markdown"><?= htmlspecialchars($data['content']); ?></span>
    </p>
<?php } ?>
