<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$search = htmlspecialchars($_GET['search']);
$search_q = '%' . $search . '%';

// title matches, content matches, author matches
$query = $pdo->prepare('SELECT id_m, title, content FROM message WHERE title LIKE ? OR content LIKE ? OR author IN (SELECT id_u FROM users WHERE username LIKE ?) ORDER BY date_published DESC');
$query->execute([$search_q, $search_q, $search_q]);
$messages = $query->fetchAll();

// brand matches, name matches, brand + name matches, sources matches, comments matches, author matches
$query = $pdo->prepare('SELECT id, brand, name FROM component WHERE brand LIKE ? OR name LIKE ? OR CONCAT(brand, \' \', name) LIKE ? OR sources LIKE ? OR id IN (SELECT component FROM component_comment WHERE content LIKE ? GROUP BY component) OR added_by IN (SELECT id_u FROM users WHERE username LIKE ?)');
$query->execute([$search_q, $search_q, $search_q, $search_q, $search_q, $search_q]);
$components = $query->fetchAll();

// username matches, bio matches
$query = $pdo->prepare('SELECT id_u, username FROM users WHERE username LIKE ? OR bio LIKE ?');
$query->execute([$search_q, $search_q]);
$users = $query->fetchAll();
?>

<h1>Résultats pour : <span class="text-muted"><?php echo $search; ?></span></h1>
<div class="jumbotron row">
    <div class="col-4">
        <h3>Messages <small class="text-muted">(<?php echo count($messages); ?> résultats)</small></h3>
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
        <h3>Composants <small class="text-muted">(<?php echo count($components); ?> résultats)</small></h3>
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
        <h3>Utilisateurs <small class="text-muted">(<?php echo count($users); ?> résultats)</small></h3>
        <ul class="list-group">
            <?php if (count($users) > 0)
                foreach ($users as $key => $user) { ?>
                <li><a href="/user/?id=<?php echo $user['id_u']; ?>"><?php echo $user['username']; ?></a></li>
            <?php } else { ?>
                <p>Rien n'a été trouvé ici :(</p>
            <?php } ?>
        </ul>
    </div>
</div>