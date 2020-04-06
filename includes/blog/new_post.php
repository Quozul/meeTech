<form id="submit-article" method="post" action="/actions/blog/add_article.php" autocomplete="off">
    <div class="form-group">
        <label for="title">Titre de l'article*</label>
        <input type="text" class="form-control" id="title" placeholder="Titre de l'article" name="title">

        <label for="content">Article*</label>
        <textarea type="text" class="form-control" id="content" name="content"></textarea>

        <label for="image">Image</label>
        <input type="file" class="form-control-file" id="image">
    </div>

    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <label class="input-group-text" for="category">Catégorie*</label>
        </div>

        <select class="custom-select" id="category" name="category" readonly>
            <option selected value=<?php echo strtolower('"' . $page_name . '"') ; ?>><?php echo $page_name ; ?></option>
        </select>
    </div>

    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <label class="input-group-text" for="language">Langue de l'article*</label>
        </div>
        <select class="custom-select" id="language" name="language">
            <option selected>Sélectionnez la langue de l'article</option>
            <?php
            $q = $pdo->prepare('SELECT * FROM language') ;
            $q->execute() ;
            $languages = $q->fetchAll() ;
            var_dump($languages) ;
            foreach($languages as $option) {
            ?>
            <option value="<?php echo $option['lang'] ; ?>"><?php echo $option['lang'] ; ?></option>
            <?php
            }
            ?>
        </select>
    </div>
</form>
