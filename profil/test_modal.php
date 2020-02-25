
<!DOCTYPE html>
<html>
<?php include('../includes/head.php'); ?>

<body class="d-flex vh-100 flex-column justify-content-between">
    <?php include('../includes/header.php'); ?>

<button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#exampleModalCenter">S'inscrir</button>

                            <!-- Add component modal/form -->
                            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalCenterTitle">S'inscrir</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="needs-validation" id="submit-component" method="post" action="/includes/hardware/add_component/" autocomplete="off" novalidate>
                                                <?php include('sign_up.php'); ?>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary" form="submit-component">S'inscrir</button>
                                        </div>
                                    </div>
                                </div>
                            </div>