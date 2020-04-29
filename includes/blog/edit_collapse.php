<div class="collapse" id="collapseEdit<?php echo $comment['id_c'] ; ?>">
  <textarea type="text" class="form-control form-control" id="editContent<?php echo $comment['id_c'] ; ?>" name="editComment"><?= $comment['content'] ; ?></textarea>
  <div class="collapse-footer">
    <button class="btn btn-secondary btn-sm" onclick="getComments()">Annuler</button>
    <button class="btn btn-primary btn-sm" onclick="submitModif(<?php echo $comment['id_c'] ; ?>)">Modifier</button>
  </div>
</div>
