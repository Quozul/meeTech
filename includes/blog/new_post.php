<div class="modal fade" id="blogPostModal" tabindex="-1" role="dialog" aria-labelledby="blogPostTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="blogPostTitle">Éditer l'article</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="submit-article" onsubmit="checkArticle()" method="post" action="/actions/blog/add_article/" autocomplete="off">
            <div class="form-group">
                <label for="title">Titre de l'article*</label>
                <input type="text" class="form-control" id="title" placeholder="Titre de l'article" name="title">

                <label for="content">Article*</label>
                <textarea type="text" class="form-control" id="content" name="content"></textarea>
                <div class="alert alert-info">Les messages sont personnalisables en <a href="https://www.markdownguide.org/basic-syntax/" target="_blank">markdown</a></div>
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="category">Catégorie*</label>
                </div>
                <select class="custom-select" id="category" name="category" readonly>
                    <option selected value="<?= strtolower($page_name) ; ?>"><?= $page_name ; ?></option>
                </select>
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="language">Langue de l'article*</label>
                </div>
                <select class="custom-select" id="language" name="language">
                    <option selected>Sélectionnez la langue de l'article</option>
                    <?php
                    $q = $pdo->prepare('SELECT lang FROM language') ;
                    $q->execute() ;
                    $languages = $q->fetchAll() ;
                    var_dump($languages) ;
                    foreach($languages as $option) {
                    ?>
                    <option value="<?= $option['lang'] ; ?>"><?= $option['lang'] ; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
        <input type="submit" class="btn btn-primary" form="submit-article" value="Publier">
      </div>
    </div>
  </div>
</div>
