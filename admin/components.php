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
                        <h5 class="modal-title" id="exampleModalCenterTitle">Ajout d'un composant</h5>
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
                    <a class="nav-link" id="v-pills-hdd-tab" data-toggle="pill" href="#v-pills-hdd" role="tab" aria-controls="v-pills-hdd" aria-selected="false">Disques durs</a>
                    <a class="nav-link" id="v-pills-ssd-tab" data-toggle="pill" href="#v-pills-ssd" role="tab" aria-controls="v-pills-ssd" aria-selected="false">SSDs</a>
                    <a class="nav-link" id="v-pills-mb-tab" data-toggle="pill" href="#v-pills-mb" role="tab" aria-controls="v-pills-mb" aria-selected="false">Cartes mère</a>
                </div>
            </div>

            <div class="col-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <?php function cpu($component)
                    { ?>
                        <tr>
                            <td><?php echo $component['brand']; ?></td>
                            <td><?php echo $component['name']; ?></td>
                            <td><?php echo $component['frequency'] . ' GHz / ' . $component['boost_frequency'] . ' GHz'; ?></td>
                            <td><?php echo $component['cores'] . 'C / ' . $component['threads'] . 'T'; ?></td>
                            <td>
                                <?php if ($component['validated'] == true) { ?>
                                    <button type="button" class="btn btn-success btn-sm disabled">Validé</button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-primary btn-sm">Valider</button>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php }

                    function gpu($component)
                    { ?>
                        <tr>
                            <td><?php echo $component['brand']; ?></td>
                            <td><?php echo $component['name']; ?></td>
                            <td><?php echo $component['core_frequency'] . ' GHz'; ?></td>
                            <td><?php echo $component['memory_type']; ?></td>
                            <td><?php echo $component['memory_capacity'] . ' Go @ ' . $component['memory_frequency'] . ' MHz'; ?></td>
                            <td>
                                <?php if ($component['validated'] == true) { ?>
                                    <button type="button" class="btn btn-success btn-sm disabled">Validé</button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-primary btn-sm">Valider</button>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php }

                    function ram($component)
                    { ?>
                        <tr>
                            <td><?php echo $component['brand']; ?></td>
                            <td><?php echo $component['name']; ?></td>
                            <td><?php echo $component['type']; ?></td>
                            <td><?php echo $component['modules'] . ' × ' . $component['capacity'] . ' Go'; ?></td>
                            <td><?php echo $component['frequency'] . ' MHz'; ?></td>
                            <td>
                                <?php if ($component['validated'] == true) { ?>
                                    <button type="button" class="btn btn-success btn-sm disabled">Validé</button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-primary btn-sm">Valider</button>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php }

                    function hdd($component)
                    { ?>
                        <tr>
                            <td><?php echo $component['brand']; ?></td>
                            <td><?php echo $component['name']; ?></td>
                            <td><?php echo $component['capacity'] . ' Go'; ?></td>
                            <td><?php echo $component['speed'] . ' tr/min'; ?></td>
                            <td>
                                <?php if ($component['validated'] == true) { ?>
                                    <button type="button" class="btn btn-success btn-sm disabled">Validé</button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-primary btn-sm">Valider</button>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php }

                    function ssd($component)
                    { ?>
                        <tr>
                            <td><?php echo $component['brand']; ?></td>
                            <td><?php echo $component['name']; ?></td>
                            <td><?php echo $component['type']; ?></td>
                            <td><?php echo $component['capacity'] . ' Go'; ?></td>
                            <td><?php echo $component['read_speed'] . ' Mo/s - ' . $component['write_speed'] . ' Mo/s '; ?></td>
                            <td>
                                <?php if ($component['validated'] == true) { ?>
                                    <button type="button" class="btn btn-success btn-sm disabled">Validé</button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-primary btn-sm">Valider</button>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php }

                    function mb($component)
                    { ?>
                        <tr>
                            <td><?php echo $component['brand']; ?></td>
                            <td><?php echo $component['name']; ?></td>
                            <td><?php echo $component['chipset']; ?></td>
                            <td><?php echo $component['socket']; ?></td>
                            <td>
                                <?php if ($component['validated'] == true) { ?>
                                    <button type="button" class="btn btn-success btn-sm disabled">Validé</button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-primary btn-sm">Valider</button>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php }

                    $GLOBALS['tbody'] = array(
                        'cpu' => 'cpu',
                        'gpu' => 'gpu',
                        'memory' => 'ram',
                        'hdd' => 'hdd',
                        'ssd' => 'ssd',
                        'motherboard' => 'mb',
                    );

                    function component_table($table_name, $pdo, $page_limit, $page)
                    {
                        $sth = $pdo->prepare('SELECT COUNT(*) FROM ' . $table_name);
                        $sth->execute();
                        $content_count = $sth->fetch()[0];

                        if ($content_count > 0) {
                            $sth = $pdo->prepare('SELECT * FROM ' .  $table_name . ' LIMIT ? OFFSET ?');
                            $sth->execute([$page_limit, ($page - 1) * $page_limit]); // remplace le ? par la valeur
                            $result = $sth->fetchAll();

                            if (count($result) > 0) {
                        ?>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Marque</th>
                                            <th scope="col">Nom</th>
                                            <?php switch ($table_name) {
                                                case 'cpu': ?>
                                                    <th scope="col">Fréquence base/boost (GHz)</th>
                                                    <th scope="col">Coeurs physiques/logiques</th>
                                                <?php break;
                                                case 'gpu': ?>
                                                    <th scope="col">Fréquence</th>
                                                    <th scope="col">Type de la mémoire</th>
                                                    <th scope="col">Capacité/fréquence de la mémoire</th>
                                                <?php break;
                                                case 'memory': ?>
                                                    <th scope="col">Type</th>
                                                    <th scope="col">Capacité</th>
                                                    <th scope="col">Fréquence</th>
                                                <?php break;
                                                case 'hdd': ?>
                                                    <th scope="col">Capacité</th>
                                                    <th scope="col">Vitesse</th>
                                                <?php break;
                                                case 'ssd': ?>
                                                    <th scope="col">Type</th>
                                                    <th scope="col">Capacité</th>
                                                    <th scope="col">Vitesse lecture/écriture</th>
                                                <?php break;
                                                case 'motherboard': ?>
                                                    <th scope="col">Chipset</th>
                                                    <th scope="col">Socket</th>
                                            <?php break;
                                            } ?>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($result as $key => $component) {
                                            $GLOBALS['tbody'][$table_name]($component);
                                        } ?>
                                    </tbody>
                                </table>

                            <?php } else { ?>
                                <p>Oops, il semblerait qu'il n'y ait rien ici :(</p>
                            <?php } ?>

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
                        <?php } else { ?>
                            <p>Oops, il semblerait qu'il n'y ait rien ici :(</p>
                    <?php }
                    }
                    ?>
                    <div class="tab-pane fade show active" id="v-pills-cpu" role="tabpanel" aria-labelledby="v-pills-cpu-tab">
                        <h3>Processeurs</h3>

                        <?php
                        component_table('cpu', $pdo, $page_limit, $page);
                        ?>
                    </div>

                    <div class="tab-pane fade" id="v-pills-gpu" role="tabpanel" aria-labelledby="v-pills-gpu-tab">
                        <h3>Cartes graphiques</h3>

                        <?php
                        component_table('gpu', $pdo, $page_limit, $page);
                        ?>
                    </div>

                    <div class="tab-pane fade" id="v-pills-ram" role="tabpanel" aria-labelledby="v-pills-ram-tab">
                        <h3>Mémoire vive</h3>

                        <?php
                        component_table('memory', $pdo, $page_limit, $page);
                        ?>
                    </div>

                    <div class="tab-pane fade" id="v-pills-hdd" role="tabpanel" aria-labelledby="v-pills-hdd-tab">
                        <h3>Disques dur</h3>

                        <?php
                        component_table('hdd', $pdo, $page_limit, $page);
                        ?>
                    </div>

                    <div class="tab-pane fade" id="v-pills-ssd" role="tabpanel" aria-labelledby="v-pills-ssd-tab">
                        <h3>SSD</h3>

                        <?php
                        component_table('ssd', $pdo, $page_limit, $page);
                        ?>
                    </div>

                    <div class="tab-pane fade" id="v-pills-mb" role="tabpanel" aria-labelledby="v-pills-mb-tab">
                        <h3>Cartes mère</h3>

                        <?php
                        component_table('motherboard', $pdo, $page_limit, $page);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include('../includes/footer.php'); ?>

</body>

</html>