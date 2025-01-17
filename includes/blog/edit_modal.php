<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalTitle">Éditer l'article</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="edit_article" onsubmit="checkArticle()" method="post" autocomplete="off" action="/actions/blog/edit_article.php?post=<?= $message_id ; ?>">
          <label for="title">Titre de l'article*</label>
          <input type="text" class="form-control mb-3" name="title" id="title" value="<?= $message['title'] ; ?>">

          <label for="content">Article*</label>
          <textarea rows="5" class="form-control mb-3" id="content" name="content"><?= $message['content'] ; ?></textarea>
          <div class="alert alert-info">Les messages sont personnalisables en <a href="https://www.markdownguide.org/basic-syntax/" target="_blank">markdown</a></div>

          <div class="input-group mb-3">
              <div class="input-group-prepend">
                  <label class="input-group-text" for="language">Langue de l'article*</label>
              </div>
              <select class="custom-select" id="language" name="language">
                  <option <?php if ($message['default_language'] == NULL) echo 'selected' ; ?>>Sélectionnez la langue de l'article</option>
                  <?php
                  $q = $pdo->query('SELECT lang FROM language') ;
                  $languages = $q->fetchAll() ;
                  foreach($languages as $option) {
                  ?>
                  <option value="<?= $option['lang'] ; ?>" <?php if ($message['default_language'] == $option['lang']) echo 'selected' ; ?>>
                    <?= $option['lang'] ; ?>
                  </option>
                  <?php
                  }
                  ?>
              </select>
              <div class="input-group-append">
                <a href="#" target="_blank"><span class="input-group-text" id="addLang">Demander une langue</span></a>
              </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <a href="/actions/blog/drop_article/?post=<?= $message_id ; ?>" class="btn btn-danger">Supprimer l'article</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
        <input type="submit" class="btn btn-primary" form="edit_article" value="Enregistrer">
      </div>
    </div>
  </div>
</div>
