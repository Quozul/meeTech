<!DOCTYPE html>
<html>
    <?php
    $page_name = 'Back-office : languages' ;
    include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php') ;
    $page_limit = 4 ;
    $page = isset($_GET['page']) ? $_GET['page'] : 1 ;
    ?>
    <body class="d-flex vh-100 flex-column justify-content-between">
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php') ; ; ?>
        <main role="main" class="container">
            <div class="jumbotron">
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#languageSubmitModal">Ajouter une langue</button>
                <div class="modal fade bd-example-modal-xl" id="languageSubmitModal" tabindex="-1" role="dialog" aria-labelledby="languageSubmitModalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="languageSubmitModalTitle">Nouvelle langue</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="submit-language" method="post" action="<?php echo $_SERVER['DOCUMENT_ROOT'] . '/actions/add_language.php/' ; ?>" autocomplete="off">
                                    <input type="text" class="form-control" id="language" name="language" placeholder="Nouvelle langue">
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary" form="submit-language">Ajouter</button>
                            </div>
                        </div>
                    </div>
                </div>
                <h2>Langages</h2>
                <hr>
                <?php
                $q = $pdo->prepare('SELECT * FROM language') ;
                $q->execute() ;
                $languages = $q->fetchAll() ;
                ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Langue</th>
                            <th scope="col">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                foreach ($languages as $value) {
                ?>
                        <tr>
                            <td><?php echo $value['lang'] ; ?></td>
                            <td>
                                <div>
                                    <button type="button" class="btn btn-outline-secondary btn-sm">Edit</button>
                                    <button type="button" class="btn btn-outline-danger btn-sm">Delete</button>
                                </div>
                            </td>
                        </tr>
                <?php
                } ;
                ?>
                    </tbody>
                </table>
            </div>
        </main>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php') ; ; ?>
    </body>
</html>