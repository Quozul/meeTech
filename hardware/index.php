<!DOCTYPE html>
<html>
    <?php include('../includes/head.php'); ?>

    <body class="d-flex vh-100 flex-column justify-content-between">
        <?php include('../includes/header.php'); ?>

        <main role="main" class="container">
            <div class="jumbotron">
                <h1>Hardware</h1>
                <ul class="nav nav-pills nav-fill">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Accueil</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Comparateur</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">Basique</a>
                            <a class="dropdown-item" href="#">Avancé</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Configurateur</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">Libre</a>
                            <a class="dropdown-item" href="#">Guidé</a>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="jumbotron">
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#exampleModalCenter">Proposer un composant</button>

                <!-- Component form -->
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
                                <?php include('includes/new_component.php'); ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary" form="submit-component">Proposer le composant</button>
                            </div>
                        </div>
                    </div>
                </div>

                <h2>Derniers composants</h2>
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
                                <div class="list-group">
                                    <!-- TODO: Add PHP loop -->
                                    <?php for ($i = 0; $i < 3; $i++) { ?>
                                    <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">Component name</h5>
                                            <small>Release date</small>
                                        </div>
                                        <dl class="row">
                                            <dt class="col-sm-3">Fréquence</dt>
                                            <dd class="col-sm-9">Nominale : 3GHz<br>Boost : 4.1GHz</dd>
                                            <dt class="col-sm-3">Coeurs</dt>
                                            <dd class="col-sm-9">Physiques : 4<br>Logiques : 8 <small class="text-muted">(hyperthreading)</small></dd>
                                            <dt class="col-sm-3">Cache</dt>
                                            <dd class="col-sm-9">L1 : 8Mo<br>L2 : 16Mo<br>L3 : 20Mo</dd>
                                        </dl>
                                    </a>
                                    <?php } ?>
                                </div>
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

                        <p class="text-muted mt-2">Composant manquant ? <a href="#" data-toggle="modal" data-target="#exampleModalCenter">Proposez-en un !</a></p>
                    </div>
                </div>
            </div>
        </main>

        <?php include('../includes/footer.php'); ?>

    </body>

</html>