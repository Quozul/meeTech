<!DOCTYPE html>
<html>
    <?php
    $page_name = 'Back-office : catégories' ;
    include($_SERVER['DOCUMENT_ROOT']. '/includes/head.php') ;
    $page_limit = 4 ;
    $page = isset($_GET['page']) ? $_GET['page'] : 1 ;
    ?>
    <body class="d-flex vh-100 flex-column justify-content-between">
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php') ; ?>
        <main role="main" class="container">
            <div class="jumbotron">
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
                            <textarea class="form-control mb-3" name="description" placeholder="Description de la catégorie"></textarea>
                        </form>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                          <button type="submit" class="btn btn-primary" form="submit-category">Ajouter</button>
                      </div>
                  </div>
              </div>
          </div>

          <h2>Catégories</h2>
          <hr>
          <?php
          $message = '' ;
          if(isset($_GET['error'])) {
            if(htmlspecialchars($_GET['error']) == 'setcategory') $message = 'Saisissez un nom de catégorie' ;
            else if (htmlspecialchars($_GET['error']) == 'elsewhere') $message = 'La valeur entrée existe déjà' ;
            if (!empty($message)) {
          ?>
          <hr>
          <div class="alert alert-danger" role="alert">
            <?php echo $message ; ?>
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
            <?php echo $message ; ?>
          </div>
          <?php
            }
          }

          $q = $pdo->prepare('SELECT name, description FROM category') ;
          $q->execute() ;
          $categories = $q->fetchAll() ;
          ?>
          <table class="table">
              <thead>
                  <tr>
                      <th scope="col">Catégorie</th>
                      <th scope="col">Description</th>
                      <th scope="col">Modifier</th>
                      <th scope="col">Supprimer</th>
                  </tr>
              </thead>
              <tbody>
                  <?php
                  foreach ($categories as $value) {
                  ?>
                  <tr>
                    <form action="../actions/categories/edit_category.php" method="post">
                      <td>
                        <input type="text" name="name" value="<?php echo $value['name'] ; ?>" class="form-control col-md-6" readonly>
                      </td>
                      <td>
                        <textarea name="description" value="<?php echo $value['description']?>" class="form-control"></textarea>
                      </td>
                      <td>
                        <input type="submit" value="Valider les modifications" class="btn btn-outline-success btn-sm">
                      </td>
                    </form>
                    <td>
                      <a href="../actions/categories/drop_category.php?category=<?php echo $value['name'] ; ?>">
                        <button type="button" class="btn btn-outline-danger btn-sm">Supprimer</button>
                      </a>
                    </td>
                  </tr>
                  <?php
                  } ;
                  ?>
              </tbody>
          </table>
        </main>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php') ; ; ?>
    </body>
</html>
