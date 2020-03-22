<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$type = $_GET['type'];
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$page_limit = isset($_GET['page-limit']) ? $_GET['page-limit'] : 5;
$order = isset($_GET['order']) ? $_GET['order'] : 'new';

// Get page count
$req = $pdo->prepare('SELECT COUNT(*) FROM component WHERE type = ?');
$req->execute([$type]);
$count = $req->fetch()[0];
$page_count = ceil($count / $page_limit);

// Change the page to an existing one
if ($count > 0 && 1 > $page || $page > $page_count)
    $page = min(max($page, 1), $page_count);

// Get all components with type
if ($order == 'last_comment')
    $req = $pdo->prepare('SELECT id_c, brand, name, validated, added_date, added_by, score FROM component AS comp WHERE type = ? ORDER BY (SELECT date_published FROM component_comment WHERE component = comp.id_c ORDER BY date_published LIMIT 1) DESC LIMIT ? OFFSET ?');
else if ($order == 'most_comment')
    $req = $pdo->prepare('SELECT id_c, brand, name, validated, added_date, added_by, score FROM component AS comp WHERE type = ? ORDER BY (SELECT count(*) FROM component_comment WHERE component = comp.id_c) DESC LIMIT ? OFFSET ?');
else if ($order == 'score')
    $req = $pdo->prepare('SELECT id_c, brand, name, validated, added_date, added_by, score FROM component AS comp WHERE type = ? ORDER BY score DESC LIMIT ? OFFSET ?');
else
    $req = $pdo->prepare('SELECT id_c, brand, name, validated, added_date, added_by, score FROM component WHERE type = ? ORDER BY added_date DESC LIMIT ? OFFSET ?');

$req->execute([$type, $page_limit, ($page - 1) * $page_limit]);
$components = $req->fetchAll();
?>

<?php if ($count > 0) { ?>
    <div class="list-group border">
        <?php foreach ($components as $key => $component) {
            $sth = $pdo->prepare('SELECT COUNT(*) FROM component_comment WHERE component = ?');
            $sth->execute([$component['id_c']]);
            $comments = $sth->fetch()[0]; ?>

            <a href="/view_component.php?id=<?php echo $component['id_c']; ?>" class="list-group-item list-group-item-action flex-column align-items-start border-0">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1"><?php echo $component['brand'] . ' ' . $component['name']; ?></h5>
                    <small>
                        <span class="badge badge-primary">Score : <?php echo $component['score']; ?></span>
                        <?php if ($component['validated'] == 0) { ?>
                            <span class="badge badge-danger">Non validé</span>
                        <?php } else { ?>
                            <span class="badge badge-success">Validé</span>
                        <?php } ?>
                        <span class="badge badge-info"><?php echo $comments . ($comments <= 1 ? ' commentaire' : ' commentaires'); ?></span>
                    </small>
                </div>

                <p class="mb-1">
                    <?php $req = $pdo->prepare('SELECT name, value, unit FROM specification JOIN specification_list ON specification.specification = specification_list.id_s WHERE component = ?');
                    $req->execute([$component['id_c']]);
                    $specs = $req->fetchAll();

                    foreach ($specs as $key => $spec) { ?>
                        <?php echo '<b>' . $spec['name'] . '</b> : ' . $spec['value'] . ' ' . $spec['unit']; ?><br>
                    <?php if ($key >= 3) break;
                    } ?>
                </p>

                <?php
                $d = new DateTime($component['added_date']);

                $username = 'Anonyme';
                $avatar = '';

                if (isset($component['added_by'])) {
                    $sth = $pdo->prepare('SELECT avatar, username FROM users WHERE id_u = ?');
                    $sth->execute([$component['added_by']]);
                    $result = $sth->fetch();

                    if ($result) {
                        $username = $result['username'];
                        $avatar = $result['avatar'];
                    }
                }

                if (!empty($avatar)) { ?>
                    <img src="/uploads/<?php echo $avatar; ?>" style="width: 16px; height: 16px; margin-top: -4px" class="mt-avatar">
                <?php } ?>
                <small>
                    Ajouté par <?php echo $username; ?> le <?php echo $d->format('d M yy'); ?>
                </small>
            </a>
        <?php } ?>
    </div>
<?php } else { ?>
    <p class="text-muted bg-white p-5 text-center border rounded">Oops, il semblerait qu'il n'y ait rien ici. Soyez le premier à ajouter un composant !</p>
<?php } ?>

<?php if (count($components) > 0) { ?>
    <!-- Pagination -->
    <nav aria-label="..." class="mt-4">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                <a class="page-link" onclick="change_page(<?php echo intval($page) - 1; ?>);" tabindex="-1">Précédent</a>
            </li>
            <?php
            for ($i = max(1, $page - 2); $i < min($page_count + 1, $page + 3); $i++) {
                if ($i == $page) {
            ?>
                    <li class="page-item active">
                        <a class="page-link" onclick="change_page(<?php echo $i; ?>);"><?php echo $i; ?> <span class="sr-only">(current)</span></a>
                    </li>
                <?php } else { ?>
                    <li class="page-item">
                        <a class="page-link" onclick="change_page(<?php echo $i; ?>);"><?php echo $i; ?></a>
                    </li>
            <?php }
            } ?>
            <li class="page-item <?php if ($page >= $page_count) echo 'disabled'; ?>">
                <a class="page-link" onclick="change_page(<?php echo intval($page) + 1; ?>);">Suivant</a>
            </li>
        </ul>
    </nav>
<?php } ?>