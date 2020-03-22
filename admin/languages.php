<!DOCTYPE html>
<html>
    <?php
    $page_name = 'Back-office : languages' ;
    include('../includes/head.php') ;
    $page_limit = 4 ;
    $page = isset($_GET['page']) ? $_GET['page'] : 1 ;
    ?>
    <body class="d-flex vh-100 flex-column justify-content-between">
        <?php include('../includes/header.php') ; ?>
        <main role="main" class="container">
            <div class="jumbotron">
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#submitModal">Ajouter une langue</button>
                <div class="modal fade bd-example-modal-xl" id="submitModal" tabindex="-1" role="dialog" aria-labelledby="submitModalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="submitModalTitle">Nouvelle langue</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <form id="submit-language" method="post" action="../actions/languages/add_language.php" autocomplete="off">
                                  <input type="text" class="form-control mb-3" id="language" name="language" placeholder="Nouvelle langue">
                                  <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="icon" name="icon" placeholder="Code HTML de l'icône (laisser vide si inexistante)">
                                    <div class="input-group-append">
                                      <a href="https://www.alt-codes.net/flags" target="_blank"><span class="input-group-text" id="flagsRef">Codes HTML drapeaux</span></a>
                                    </div>
                                  </div>
                                  <input type="text" class="form-control mb-3" id="label" name="label" placeholder="Code de la langue (2 caractères)">
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
                <p><a href="https://www.alt-codes.net/flags" target="_blank">Liens unicode des drapeaux</a></p>
                <?php
                if(isset($_GET['error'])) {
                  if(htmlspecialchars($_GET['error']) == 'setlang') $message = 'Saisissez un nom de langue' ;
                  else if (htmlspecialchars($_GET['error']) == 'elsewhere') $message = 'La valeur entrée existe déjà' ;
                  else $message = '' ;
                  if (!empty($message)) {
                ?>
                <hr>
                <div class="alert alert-danger" role="alert">
                  <?php echo $message ; ?>
                </div>
                <?php
                  }
                }

                $q = $pdo->prepare('SELECT lang, icon, label FROM language') ;
                $q->execute() ;
                $languages = $q->fetchAll() ;
                ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Langue</th>
                            <th scope="col">Icône</th>
                            <th scope="col">Label</th>
                            <th scope="col">Modifier</th>
                            <th scope="col">Supprimer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($languages as $value) {
                        ?>
                        <tr>
                          <form action="../actions/languages/edit_language.php" method="post">
                            <td>
                              <div class="row">
                                <?php if (strlen($value['icon']) != 0) echo $value['icon'] ; else echo '&#127987' ; ?>
                                <input type="text" name="language" value="<?php echo $value['lang'] ; ?>" class="form-control col-md-6" readonly>
                              </div>
                            </td>
                            <td>
                                <input type="text" name="icon" value="<?php echo htmlspecialchars(substr($value['icon'], 0, 8)) . ' ' . htmlspecialchars(substr($value['icon'], 8, 8)) ; ?>" class="form-control">
                            </td>
                            <td>
                              <input type="text" name="label" value="<?php echo $value['label'] ; ?>" class="form-control col-md-3"></td>
                            </td>
                            <td>
                              <input type="submit" value="Valider les modifications" class="btn btn-outline-success btn-sm">
                            </td>
                          </form>
                          <td>
                            <button type="button" class="btn btn-outline-danger btn-sm">Supprimer</button>
                          </td>
                        </tr>
                        <?php
                        } ;
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
        <?php include('../includes/footer.php') ; ; ?>
    </body>
</html>
