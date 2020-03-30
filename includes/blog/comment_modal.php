<?php function commentModal ($comment) { ?>
<div class="collapse" id="collapseResp<?php echo $comment ; ?>">
    <div class="card card-body">
        <button type="button" class="close" data-dismiss="collapse" aria-label="Close">
          <span aria-hidden="true" class="float-right">&times;</span>
        </button>
        <form id="submit-response" method="post" action="includes/blog/new_response.php/">
            <div class="form-group">
                <textarea type="text" class="form-control form-control-lg" id="content" name="content"></textarea>
            </div>
            <div class="collapse-footer">
                <button type="submit" class="btn btn-primary btn-sm" form="submit-response">Publier</button>
            </div>
        </form>
    </div>
</div>
<?php } ?>
