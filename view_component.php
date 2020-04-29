<!DOCTYPE html>
<html lang="fr">
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

    <main class="container">
        <?php $comp_id = $_GET['id'];

        // Fetch component
        $req = $pdo->prepare('SELECT name, brand, sources, added_by, added_date, score, validated FROM component WHERE id_c = ?');
        $req->execute([$comp_id]);
        $component = $req->fetch();

        // Fetch specs
        $req = $pdo->prepare('SELECT name, value, unit, data_type FROM specification JOIN specification_list ON specification.specification = specification_list.id_s WHERE component = ?');
        $req->execute([$comp_id]);
        $specs = $req->fetchAll();
        ?>

        <div class="modal fade" id="edit-component-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter un composant</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php include('includes/hardware/edit_component_form.php');
                        form($pdo, $comp_id); ?>
                    </div>
                    <div class="modal-footer">
                        <?php if (isset($_SESSION['userid'])) { ?>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-edit-component-modal">Annuler</button>
                            <button type="button" class="btn btn-primary" onclick="update_component();">Mettre à jour</button>
                        <?php } else { ?>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-edit-component-modal">Fermer</button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn-sm btn-secondary float-right" data-toggle="modal" data-target="#edit-component-modal">Modifier le composant</button>
        <button class="btn btn-primary float-left mr-2" onclick="history.back();">« Retour</button>
        <h2>Composant</h2>

        <div class="jumbotron" id="content">
            <?php if ($component) { ?>
                <h2><?php echo $component['brand'] . ' ' . $component['name']; ?></h2>
                <p class="text-muted">
                    <?php // TODO: Clean this "p" tag up
                    $d = new DateTime($component['added_date']);

                    $username = 'Anonyme';
                    $avatar = '';

                    if (isset($component['added_by'])) {
                        $sth = $pdo->prepare('SELECT avatar, username FROM users WHERE id_u = ?');
                        $sth->execute([$component['added_by']]); // remplace le ? par la valeur
                        $result = $sth->fetch();

                        if ($result) {
                            $username = $result['username'];
                            $avatar = $result['avatar'];
                        }
                    }

                    if (!empty($avatar)) {
                    ?>
                        <img alt="Author's avatar" src="/uploads/<?php echo $avatar; ?>" style="width: 16px; height: 16px; margin-top: -4px" class="mt-avatar">
                    <?php } ?>
                    Ajouté par <a href="/user/?id=<?php echo $component['added_by']; ?>"><?php echo $username; ?></a> le <?php echo $d->format('d M yy'); ?>
                    <?php

                    if ($component['validated'] == 0) {
                        echo '<span class="badge badge-danger float-right" title="Les informations n\'ont pas été vérifiées">Non validé</span>';
                    } else {
                        echo '<span class="badge badge-success float-right" title="Les informations de ce composants sont correctes">Validé</span>';
                    }
                    ?>
                    <span class=" badge badge-primary float-right mr-1">Score : <?php echo $component['score']; ?></span>
                </p>

                <hr>
                <h4>Caractéristiques</h4>

                <?php foreach ($specs as $key => $spec) {
                    if ($spec['data_type'] != 'list')
                        echo '<b>' . $spec['name'] . '</b> : ' . $spec['value'] . ' ' . $spec['unit'] . '<br>';
                    else {
                        // Fetch option
                        $req = $pdo->prepare('SELECT option FROM specification_option WHERE id_o = ?');
                        $req->execute([$spec['value']]);
                        $option = $req->fetch()[0];
                        echo '<b>' . $spec['name'] . '</b> : ' . $option . ' ' . $spec['unit'] . '<br>';
                    }
                } ?>

                <hr>
                <h4>Sources</h4>
                <p><?php echo !empty($component['sources']) ? $component['sources'] : 'Aucune'; ?></p>
            <?php } else { ?>
                <h1>Ce composant n'existe pas !</h1>
            <?php } ?>
        </div>

        <?php if ($component) { ?>
            <!-- Comments -->
            <h3>Commentaires</h3>
            <div class="jumbotron">
                <div id="comments"></div>
                <hr>
                <?php if (isset($_SESSION['userid']) && !empty($_SESSION['userid'])) { ?>
                    <form>
                        <div class="form-group">
                            <label for="comment" id="comment-label">Commentaire</label>
                            <textarea class="form-control" id="comment" name="comment" placeholder="Tapez un commentaire pour ce composant..."></textarea>
                        </div>
                        <button type="button" onclick="submit_comment(<?php echo $comp_id; ?>);" class="btn btn-primary">Poster</button>
                    </form>
                <?php } else { ?>
                    <div class="alert alert-info">Vous devez être connecté pour poster un commentaire.</div>
                <?php } ?>

                <script>
                    let answers = null;
                    const comp_id = <?php echo $comp_id; ?>;

                    function update_comments() {
                        const url_params = new URLSearchParams(window.location.search);
                        const comp_id = url_params.get('id');
                        getHtmlContent('/includes/hardware/comments.php', `id=${comp_id}`).then((res) => {
                            document.getElementById('comments').innerHTML = res;
                        });
                    }

                    function submit_comment() {
                        let query = `id=${comp_id}&comment=${document.getElementById('comment').value}`;

                        const answers = comment_get_answer();
                        if (answers != null)
                            query += `&answers=${answers}`;

                        request('/actions/hardware/submit_comment.php', query).then((e) => {
                            console.log(e.resonse);

                            update_comments();

                            document.getElementById('comment').value = '';
                        });
                    }

                    function comment_get_answer() {
                        return answers;
                    }

                    function aswer_comment(comment_id) {
                        const comments = document.getElementsByClassName('comment');
                        const comment = document.getElementById(`comment-${comment_id}`);
                        const comment_label = document.getElementById('comment-label');

                        if (!comment.classList.contains('bg-light')) {

                            for (const key in comments)
                                if (comments.hasOwnProperty(key)) {
                                    const element = comments[key];
                                    if (element.classList.contains('bg-light'))
                                        element.classList.remove('bg-light');
                                }

                            comment.classList.add('bg-light');
                            answers = comment_id;

                            comment_label.innerHTML = `Répondre à ${comment.getElementsByClassName('comment-author')[0].innerHTML}`;
                        } else {
                            comment.classList.remove('bg-light');
                            comment_label.innerHTML = `Commentaire`;
                            answers = null;
                        }
                    }

                    function remove_comment(comment_id) {
                        let query = `id=${comment_id}`;

                        request('/actions/hardware/delete_comment.php', query).then((e) => {
                            console.log(e.resonse);
                            update_comments();
                        });
                    }

                    function page_update_component() {
                        document.getElementById('close-edit-component-modal').click();
                        window.location.reload();
                    }

                    update_comments();
                </script>
            </div>
        <?php } ?>
    </main>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

</body>

</html>