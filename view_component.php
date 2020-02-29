<?php
$page_name = 'Composant n°' . $_GET['id'];

// Columns names and description
$cols = json_decode(file_get_contents('includes/hardware/specifications.json'), true);

// Get component's informations
// $sth = $pdo->prepare('SELECT * FROM component WHERE id = ?');
// $sth->execute([$_GET['id']]);
// $component = $sth->fetch();
// $specs = json_decode($component['specifications'], true);

// $page_description = $cols[$component['type']]['name'] . ' ' . (isset($specs['brand']) ? $specs['brand'] : 'NoBrand') . ' ' . (isset($specs['name']) ? $specs['name'] : 'NoName') . ' - ' . $component['score'] . ' points';
?>

<!DOCTYPE html>
<html>
<?php include('includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">
    <?php include('includes/header.php'); ?>

    <main role="main" class="container">
        <h1>
            <a class="btn btn-primary" role="button" href="javascript: history.go(-1)">« Retour</a>
            <?php
            $sth = $pdo->prepare('SELECT * FROM component WHERE id = ?');
            $sth->execute([$_GET['id']]);
            $component = $sth->fetch();
            $specs = json_decode($component['specifications'], true);

            echo $cols[$component['type']]['name'];
            ?>
        </h1>

        <div class="jumbotron">
            <?php if ($component['validated'] == 0) { ?>
                <form action="/edit_component.php" method="post">
                    <button type="submit" class="btn btn-primary float-right" name="id" value="<?php echo $component['id']; ?>">Proposer une modification</button>
                </form>
            <?php } ?>

            <h2>
                <?php
                echo (isset($specs['brand']) ? $specs['brand'] : 'NoBrand') . ' ' . (isset($specs['name']) ? $specs['name'] : 'NoName');
                ?>
            </h2>
            <p class="text-muted">
                <?php
                $d = new DateTime($component['added_date']);

                $username = 'Anonyme';

                if (isset($component['added_by'])) {
                    $sth = $pdo->prepare('SELECT username FROM users WHERE id_u = ?');
                    $sth->execute([$component['added_by']]); // remplace le ? par la valeur
                    $result = $sth->fetch();

                    if ($result)
                        $username = $result['username'];
                }

                // TODO: Add link to user's profile
                echo 'Ajouté par ' . $username . ' le ' . $d->format('d M yy');

                if ($component['validated'] == 0) {
                    echo '<span class="badge badge-danger float-right" data-toggle="tooltip" data-placement="top" title="Les informations n\'ont pas été vérifiées">Non validé</span>';
                } else {
                    echo '<span class="badge badge-success float-right" data-toggle="tooltip" data-placement="top" title="Les informations de ce composants sont correctes">Validé</span>';
                }
                ?>
                <span class="badge badge-primary float-right mr-1">Score : <?php echo $component['score']; ?></span>
            </p>
            <hr>
            <?php foreach ($cols[$component['type']]['specs'] as $key => $value) {
                echo '<b>' . $value['name'] . '</b> : ' . (isset($specs[$key]) ? (isset($value['values']) ? $value['values'][$specs[$key]] : $specs[$key]) : 'Inconnu') . ' ' . (isset($value['unit']) ? $value['unit'] : "") . '<br>';
            } ?>
            <hr>
            <h5>Sources</h5>
            <?php echo str_replace('\n', '<br>', $component['sources']); ?>
        </div>

        <!-- Comments -->
        <h3>Commentaires</h3>
        <div class="jumbotron">
            <div id="comments">
                <?php include('includes/hardware/comments.php'); ?>
            </div>
            <hr>
            <?php if (isset($_SESSION['userid']) && !empty($_SESSION['userid'])) { ?>
                <form>
                    <div class="form-group">
                        <label for="comment">Commentaire</label>
                        <textarea class="form-control" id="comment" name="comment" placeholder="Tapez un commentaire pour ce composant..."></textarea>
                    </div>
                    <button type="button" onclick="submit_comment(<?php echo $component['id']; ?>);" class="btn btn-primary">Poster</button>
                </form>
            <?php } else { ?>
                <div class="alert alert-info">Vous devez être connecté pour poster un commentaire.</div>
            <?php } ?>
        </div>
    </main>

    <script>
        function submit_comment(component_id) {
            request('/actions/hardware/submit_comment.php', `id=${component_id}&comment=${document.getElementById('comment').value}`).then((e) => {
                console.log(e.resonse);

                getHtmlContent('/includes/hardware/comments.php', `id=${component_id}`).then((res) => {
                    document.getElementById('comments').innerHTML = res.getElementsByTagName('body')[0].innerHTML;
                });

                document.getElementById('comment').value = '';
            })
        }
    </script>

    <?php include('includes/footer.php'); ?>
</body>

</html>