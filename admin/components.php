<?php
include('../config.php');
$page_limit = 2;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
?>

<!DOCTYPE html>
<html>
<?php include('../includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">
    <?php include('../includes/header.php'); ?>

    <main role="main" class="container">
        <!-- <div class="jumbotron"> -->
        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#exampleModalCenter">Proposer un composant</button>

        <!-- Add component modal/form -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Ajoût d'un composant</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php include('../includes/hardware/new_component.php'); ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary" form="submit-component">Proposer le composant</button>
                    </div>
                </div>
            </div>
        </div>

        <h2>Composants</h2>
        <hr>

        <div class="row">
            <div class="col-3">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active" id="v-pills-cpu-tab" data-toggle="pill" href="#v-pills-cpu" role="tab" aria-controls="v-pills-cpu" aria-selected="true">Processeurs</a>
                    <a class="nav-link" id="v-pills-gpu-tab" data-toggle="pill" href="#v-pills-gpu" role="tab" aria-controls="v-pills-gpu" aria-selected="false">Cartes graphiques</a>
                    <a class="nav-link" id="v-pills-ram-tab" data-toggle="pill" href="#v-pills-ram" role="tab" aria-controls="v-pills-ram" aria-selected="false">Mémoire vive</a>
                    <a class="nav-link" id="v-pills-rom-tab" data-toggle="pill" href="#v-pills-rom" role="tab" aria-controls="v-pills-rom" aria-selected="false">Disques durs et SSD</a>
                    <a class="nav-link" id="v-pills-mb-tab" data-toggle="pill" href="#v-pills-mb" role="tab" aria-controls="v-pills-mb" aria-selected="false">Cartes mère</a>
                </div>
            </div>

            <div class="col-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-cpu" role="tabpanel" aria-labelledby="v-pills-cpu-tab">
                        <h3>Processeurs</h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Marque</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Fréquence base/boost (GHz)</th>
                                    <th scope="col">Coeurs physiques/logiques</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- TODO: Add PHP loop -->
                                <?php
                                $sth = $pdo->prepare('SELECT COUNT(*) FROM cpu');
                                $sth->execute();
                                $content_count = $sth->fetch()[0];

                                $sth = $pdo->prepare('SELECT * FROM cpu LIMIT ? OFFSET ?');
                                $sth->execute([$page_limit, ($page - 1) * $page_limit]); // remplace le ? par la valeur
                                $result = $sth->fetchAll();

                                foreach ($result as $key => $cpu) {
                                ?>
                                    <tr>
                                        <td><?php echo $cpu['brand']; ?></td>
                                        <td><?php echo $cpu['name']; ?></td>
                                        <td><?php echo $cpu['frequency'] . '/' . $cpu['boost_frequency']; ?></td>
                                        <td><?php echo $cpu['cores'] . 'C/' . $cpu['threads'] . 'T'; ?></td>
                                        <td>
                                            <?php if ($cpu['validated'] == true) { ?>
                                                <button type="button" class="btn btn-success btn-sm disabled">Validé</button>
                                            <?php } else { ?>
                                                <button type="button" class="btn btn-primary btn-sm">Valider</button>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <nav aria-label="..." class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php
                                $page_count = $content_count / $page_limit;
                                ?>
                                <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                                    <a class="page-link" href="<?php echo '?page=' . (intval($page) - 1); ?>" tabindex="-1">Précédent</a>
                                </li>
                                <?php
                                for ($i = 1; $i < $page_count + 1; $i++) {
                                    if ($i == $page) {
                                ?>
                                        <li class="page-item active">
                                            <a class="page-link" href="<?php echo '?page=' . $i; ?>"><?php echo $i; ?> <span class="sr-only">(current)</span></a>
                                        </li>
                                    <?php } else { ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?php echo '?page=' . $i; ?>"><?php echo $i; ?></a>
                                        </li>
                                <?php }
                                } ?>
                                <li class="page-item <?php if ($page >= $page_count) echo 'disabled'; ?>">
                                    <a class="page-link" href="<?php echo '?page=' . (intval($page) + 1); ?>">Suivant</a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <div class="tab-pane fade" id="v-pills-gpu" role="tabpanel" aria-labelledby="v-pills-gpu-tab">
                        <h3>Cartes graphiques</h3>
                        <p>Oops, il semblerait qu'il n'y ait rien ici :(</p>
                    </div>

                    <div class="tab-pane fade" id="v-pills-ram" role="tabpanel" aria-labelledby="v-pills-ram-tab">
                        <h3>Mémoire vive</h3>
                        <p>Oops, il semblerait qu'il n'y ait rien ici :(</p>
                    </div>

                    <div class="tab-pane fade" id="v-pills-rom" role="tabpanel" aria-labelledby="v-pills-rom-tab">
                        <h3>Disques dur et SSD</h3>
                        <p>Oops, il semblerait qu'il n'y ait rien ici :(</p>
                    </div>

                    <div class="tab-pane fade" id="v-pills-mb" role="tabpanel" aria-labelledby="v-pills-mb-tab">
                        <h3>Cartes mère</h3>
                        <p>Oops, il semblerait qu'il n'y ait rien ici :(</p>
                        <!-- <div class="list-group">
                                    <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">List group item heading</h5>
                                            <small>3 days ago</small>
                                        </div>
                                        <p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
                                        <small>Donec id elit non mi porta.</small>
                                    </a>
                                </div> -->
                    </div>
                </div>

                <p class="text-muted">Composant manquant ? <a href="#" data-toggle="modal" data-target="#exampleModalCenter">Proposez-en un !</a></p>
            </div>
        </div>
        <!-- </div> -->
    </main>

    <?php include('../includes/footer.php'); ?>

</body>

</html>