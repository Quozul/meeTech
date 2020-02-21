<form id="submit-component" method="post" action="/includes/blog/add_component/" autocomplete="off">
    <div class="form-group">
        <label for="title">Titre de l'article*</label>
        <input type="text" class="form-control" id="title" placeholder="Titre de l'article" name="title">

        <label for="content">Article*</label>
        <textarea type="text" class="form-control form-control-lg" id="content" name="content"></textarea>

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
</form>