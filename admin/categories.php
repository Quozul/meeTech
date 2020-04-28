<!DOCTYPE html>
<html>
    <?php
    $page_name = 'Back-office : catégories' ;
    include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php') ;
    $page_limit = 4 ;
    $page = isset($_GET['page']) ? $_GET['page'] : 1 ;
    ?>
    <body class="d-flex vh-100 flex-column justify-content-between">
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php') ; ?>
        <main role="main" class="container">

          <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#submitModal">Ajouter une catégorie</button>
          <div class="modal fade bd-example-modal-xl" id="submitModal" tabindex="-1" role="dialog" aria-labelledby="submitModalTitle" aria-hidden="true">
              <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="submitModalTitle">Nouvelle catégorie</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form id="submit-category" method="post" action="../actions/categories/add_category.php" autocomplete="off">
                            <input type="text" class="form-control mb-3" name="name" placeholder="Nouvelle catégorie">
                            <input type="text" class="form-control mb-3" name="description" placeholder="Description de la catégorie">
                        </form>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                          <button type="submit" class="btn btn-primary" form="submit-category">Ajouter</button>
                      </div>
                  </div>
              </div>
          </div>
          <h1>Catégories</h1>

          <?php
          $message = '' ;
          if(isset($_GET['error'])) {
            if(htmlspecialchars($_GET['error']) == 'setcategory') $message = 'Saisissez un nom de catégorie' ;
            else if (htmlspecialchars($_GET['error']) == 'elsewhere') $message = 'La valeur entrée existe déjà' ;
            if (!empty($message)) {
          ?>
          <hr>
          <div class="alert alert-danger" role="alert">
            <?= $message ; ?>
          </div>
          <?php
            }
          }

          $message = '' ;
          if(isset($_GET['success'])) {
            if(htmlspecialchars($_GET['success']) == 'edit') $message = 'Catégorie éditée' ;
            else if (htmlspecialchars($_GET['success']) == 'add') $message = 'Catégorie ajoutée' ;
            else if (htmlspecialchars($_GET['success']) == 'drop') $message = 'Catégorie supprimée' ;
            if (!empty($message)) {
          ?>
          <hr>
          <div class="alert alert-success" role="alert">
            <?= $message ; ?>
          </div>
          <?php
            }
          }
          ?>

          <div class="jumbotron">
            <?php
            $q = $pdo->prepare('SELECT name, description FROM category') ;
            $q->execute() ;
            $categories = $q->fetchAll() ;
            ?>

            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
              <?php foreach ($categories as $cat) { ?>
              <li class="nav-item">
                <a class="nav-link" id="pills-<?= $cat['name'] ; ?>-tab" data-toggle="pill" href="#pills-<?= $cat['name'] ; ?>" role="tab" aria-controls="pills-<?= $cat['name'] ; ?>" aria-selected="<?= $cat['name'] ; ?>"><?= $cat['name'] ; ?></a>
              </li>
              <?php } ?>
            </ul>

            <div class="tab-content" id="pills-tabContent">
              <?php foreach ($categories as $cat) { ?>
              <div class="tab-pane fade" id="pills-<?= $cat['name'] ; ?>" role="tabpanel" aria-labelledby="pills-<?= $cat['name'] ; ?>-tab">
                <table class="table">
                  <tbody>
                      <tr>
                        <form action="../actions/categories/edit_category.php" method="post">
                          <td>
                            <input type="text" name="name" id="name-<?= $cat['name']?>" value="<?= $cat['name'] ; ?>" class="form-control" readonly>
                          </td>
                          <td>
                            <textarea type="text" name="description" id="description-<?= $cat['name']?>" class="form-control col-md-12 text">
                              <?= $cat['description']?>
                            </textarea>
                          </td>
                          <td>
                            <input type="submit" value="Valider les modifications" class="btn btn-outline-success btn-sm float-right">
                          </td>
                        </form>
                        <td>
                          <a href="../actions/categories/drop_category.php?category=<?= $cat['name'] ; ?>">
                            <button type="button" class="btn btn-outline-danger btn-sm">Supprimer</button>
                          </a>
                        </td>
                      </tr>
                  </tbody>
                </table>
                <hr>

                <div class="mb-3">
                  <button type="button" class="btn btn-primary" onclick="get('<?= $cat['name'] ; ?>', 'all', 'messages')">Tous les messages</button>
                  <button type="button" class="btn btn-warning" onclick="get('<?= $cat['name'] ; ?>', 'signaled', 'messages')">Messages signalés</button>
                  <button type="button" class="btn btn-primary" onclick="get('<?= $cat['name'] ; ?>', 'all', 'comments')">Tous les commentaires</button>
                  <button type="button" class="btn btn-warning" onclick="get('<?= $cat['name'] ; ?>', 'signaled', 'comments')">Commentaires signalés</button>
                </div>

                <div id="display-<?= $cat['name'] ; ?>"></div>

              </div>
              <?php } ?>
            </div>
        </main>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php') ; ; ?>
        <script src="../categories.js" charset="utf-8"></script>
    </body>
</html>
