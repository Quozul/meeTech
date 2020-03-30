<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalTitle">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="edit-article" method="post" action="/meeTech/actions/blog/edit_article/?post=<?php echo $message['id_m'] ; ?>" autocomplete="off">
            <div class="form-group">
                <label for="title">Titre de l'article*</label>
                <input type="text" class="form-control" name="title" value="<?php echo $message['title'] ; ?>">

                <label for="content">Article*</label>
                <textarea rows="5" type="text" class="form-control" id="content" name="content"><?php echo $message['content'] ; ?></textarea>
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="language">Langue de l'article*</label>
                </div>
                <select class="custom-select" id="language" name="language">
                    <option <?php if ($message['default_language'] == NULL) echo 'selected' ; ?>>Sélectionnez la langue de l'article</option>
                    <?php
                    $q = $pdo->prepare('SELECT lang FROM language') ;
                    $q->execute() ;
                    $languages = $q->fetchAll() ;
                    var_dump($languages) ;
                    foreach($languages as $option) {
                    ?>
                    <option value="<?php echo $option['lang'] ; ?>" <?php if ($message['default_language'] == $option['lang']) echo 'selected' ; ?>>
                      <?php echo $option['lang'] ; ?>
                    </option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" form="edit-article">Save changes</button>
      </div>
    </div>
  </div>
</div>
