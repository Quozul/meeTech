<form id="translate_form" onsubmit="checkArticle()" method="post" autocomplete="off" action="../actions/blog/add_translation/?post=<?= $message_id ; ?>">
  <label for="title">Titre traduit*</label>
  <input type="text" class="form-control mb-3" name="title" id="title">

  <label for="content">Article traduit*</label>
  <textarea rows="5" type="text" class="form-control mb-3" id="content" name="content"></textarea>

  <div class="input-group mb-3">
      <div class="input-group-prepend">
          <label class="input-group-text" for="language">Langue de traduction*</label>
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
    <input type="submit" class="btn btn-primary" value="Envoyer">
</form>
