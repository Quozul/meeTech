<!DOCTYPE html>
<html>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'); ?>

<body>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>
    <main>
        <form id="add_badge_form" method="post" action="/admin/actions/badges/add_badge.php">
            <div class="form-group">
                <label for="formGroupNameInpute">Nom du badge</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Nom du badges">
            </div>
            <div class="form-group">
                <label for="formGroupDescriptionInput">Description</label>
                <input type="text" class="form-control" id="description" name="description" placeholder="Description">
            </div>
            <div class="form-group">
                <label for="formGroupGlbPermInput">Permission Globale</label>
                <input type="number" class="form-control" id="glbperm" name="glbperm" placeholder="Permission globale">
            </div>
            <div class="form-group">
                <label for="formGroupObtentionInput">Obtention</label>
                <input type="number" class="form-control" id="obtention" name="obtention" placeholder="Obtention">
            </div>
            <input type="submit" class="btn btn-primary" value="Ajouter le badge">
        </form>
    </main>
</body>


</html>