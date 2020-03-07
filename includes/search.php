<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$search = htmlspecialchars($_GET['search']);
$search_q = '%' . $search . '%';

$query = $pdo->prepare('SELECT id_m, title, content FROM message WHERE title LIKE ? OR content LIKE ? ORDER BY date_published DESC');
$query->execute([$search_q, $search_q]);
$messages = $query->fetchAll();

$query = $pdo->prepare('SELECT id, brand, name FROM component WHERE brand LIKE ? OR name LIKE ? OR CONCAT(brand, \' \', name) LIKE ?');
$query->execute([$search_q, $search_q, $search_q]);
$components = $query->fetchAll();

$query = $pdo->prepare('SELECT id_u, username FROM users WHERE username LIKE ?');
$query->execute([$search_q]);
$users = $query->fetchAll();
?>

<h1>Résultats pour : <span class="text-muted"><?php echo $search; ?></span></h1>
<div class="jumbotron row">
    <div class="col-4">
        <h3>Messages</h3>
        <ul class="list-group">
            <?php if (count($messages) > 0)
                foreach ($messages as $key => $message) { ?>
                <li><a href="/article/?post=<?php echo $message['id_m'] ?>"><?php echo $message['title']; ?></a></li>
            <?php } else { ?>
                <p>Rien n'a été trouvé ici :(</p>
            <?php } ?>
        </ul>
    </div>
    <div class=" col-4">
        <h3>Composants</h3>
        <ul class="list-group">
            <?php if (count($components) > 0)
                foreach ($components as $key => $component) { ?>
                <li><a href="/view_component/?id=<?php echo $component['id'] ?>"><?php echo $component['brand'] . ' ' . $component['name']; ?></a></li>
            <?php } else { ?>
                <p>Rien n'a été trouvé ici :(</p>
            <?php } ?>
        </ul>
    </div>
    <div class="col-4">
        <h3>Utilisateurs</h3>
        <ul class="list-group">
            <?php if (count($users) > 0)
                foreach ($users as $key => $user) { ?>
                <li><?php echo $user['username']; ?></li>
            <?php } else { ?>
                <p>Rien n'a été trouvé ici :(</p>
            <?php } ?>
        </ul>
    </div>
</div>