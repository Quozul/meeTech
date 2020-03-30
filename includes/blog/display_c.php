<?php
function displayComment ($comm_index, $increment, $comment, $pdo) {
    $user = author_query($comment['author'], $pdo) ;
?>
<div class="container border-left border-dark mr-3 mb-3 p-3" style="margin-left:<?php echo $increment . 'rem'?>">
    <!-- get image from user -->
    <header>
        <div class="float-right">
            <span class="badge badge-pill badge-success"><?php echo $comment['note'] ; ?></span>
            <span class="badge badge-pill badge-danger">!</span>
        </div>
        <a href="#"><h6><?php echo $user ; ?></h6></a>
        <small class="text-muted">
            <?php
            echo "Published on " . $comment['date_published'] ;
            if ($comment['date_edited'] != NULL) echo ", last edited on " . $comment['date_edited'] ;
            ?>
        </small>
    </header>
    <div class="markdown"><?php echo $comment['content'] ; ?></div>
    <small class="text-muted"><a data-toggle="collapse" href="#collapseResp<?php echo $comment['id_c'] ; ?>" aria-expanded="false" aria-controls="collapseResp<?php echo $comment['id_c'] ; ?>">Répondre</a></small>
    <?php commentModal($comment['id_c']) ; ?>

    <hr>
    <?php
    getRelatedComments($comment['id_c'], $comment['parent_message'], $pdo, $increment) ;
    ?>
</div>

<?php } ?>
