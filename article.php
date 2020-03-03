<!DOCTYPE html>
<html>
    <?php
        include('includes/head.php') ;
        include('includes/author_query.php') ;
        $page_limit = 10 ;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $exists = 0 ;
    ?>

    <body class="d-flex vh-100 flex-column justify-content-between">
        <?php include('includes/header.php') ; ?>
        <main role="main" class="container">
            <section class="jumbotron">

<!-- The $_GET['post'] isn't set -->
            <?php
            if (!isset($_GET['post']) || is_null($_GET['post'])) {
                include('includes/nothing.php')  ;
            } else {
                $message_id = strip_tags($_GET['post']) ;
                $query = $pdo->prepare('SELECT COUNT(*) FROM message WHERE id_m = ?') ;
                $query->execute([$message_id]) ;
                $exists = $query->fetch()[0] ;
                if ($exists == 0) {
                ?>

<!-- The $_GET['post'] is set but no matching article in DB -->
                    <h1>Whoups, this article doesn't exist !</h1>
                    <hr>
                    <a href="blog/"><button type="button" class="btn btn-primary">Go to Blog</button></a>
                    <a href="forum/"><button type="button" class="btn btn-primary">Go to Forum</button></a>

<!-- Valid article display -->
                <?php
                } else {
                    $query = $pdo->prepare('SELECT author, title, content, date_published, date_edited, default_language, note, category FROM message WHERE id_m = ?') ;
                    $query->execute([$message_id]) ;
                    $message = $query->fetchAll()[0] ;
                    $author = author_query($message['author'], $pdo) ;
            ?>
                <h1><?php echo $message['title'] ; ?></h1>
                <div class="float-right">
                    <button type="button" class="btn btn-outline-secondary btn-sm">Edit</button>
                    <button type="button" class="btn btn-outline-dark btn-sm">Delete</button>
                    <button type="button" class="btn btn-outline-danger btn-sm">Report</button>
                </div>
                <small class="text-muted">
                    Published on <?php echo $message['date_published'] ?> by 
                    <?php echo '<a href="#">' . $author . '</a>' ; ?>
                </small>
                <hr>

                <img src="..." class="rounded float-left" alt="...">
                <p><?php echo $message['content'] ; ?></p>
                <button type="button" class="btn btn-outline-primary">+ <?php echo $message['note'] ; ?></button>
            <?php
                }
            }
            ?>
            </section>

            <?php
            if ($exists != 0 && !is_null($message[0])) {
            ?>
<!-- Comments display -->
            <section class="jumbotron">
                <?php
                $query = $pdo->prepare('SELECT COUNT(id_c) FROM comment WHERE parent_message = ? AND parent_comment = ?') ;
                $query->execute([$message_id, 0]) ;
                $elements = $query->fetch()[0] ;
                if ($elements == 0) {
                ?>
                    <p>Would you like to <a href="#collapseResp0">comment</a> on this ?</p>
                <?php
                } else {
                    $query = $pdo->prepare('SELECT id_c, author, parent_message, content, date_published, date_edited, note FROM comment WHERE parent_message = ? AND parent_comment = ?') ;
                    $query->execute([$message_id, 0]) ;
                    $comments = $query->fetchAll() ;

                    $offset = $page_limit * ($page - 1) ;
                    for ($i = $offset ; $i < $offset + $page_limit && $i < $elements ; $i++) {
                        $increment = 1 ;
                        displayComment($i, $increment, $comments[$i], $pdo) ;
                    }
                    ?>
                    <button type="button" class="btn btn-primary" data-toggle="collapse" href="#collapseResp0" aria-expanded="false" aria-controls="collapseResp">Comment</button>
            <?php
                }
            commentModal(0) ;
            }
            ?>
            </section>
        </main>
        <?php include('includes/footer.php') ; ?>
    </body>
</html>


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
        <h6><?php echo $user ; ?></h6>
        <small class="text-muted">
            <?php
            echo "Published on " . $comment['date_published'] ;
            if ($comment['date_edited'] != NULL) echo ", last edited on " . $comment['date_edited'] ;
            ?>
        </small>
    </header>
    <p><?php echo $comment['content'] ; ?></p>
    <small class="text-muted"><a data-toggle="collapse" href="#collapseResp<?php echo $comment['id_c'] ; ?>" aria-expanded="false" aria-controls="collapseResp<?php echo $comment['id_c'] ; ?>">Répondre</a></small>
    <?php commentModal($comment['id_c']) ; ?>
    
    <hr>
    <?php
    getRelatedComments($comment['id_c'], $comment['parent_message'], $pdo, $increment) ;
    ?>
</div>

<?php
}
function getRelatedComments ($parent_comm, $id_m, $pdo, $indent) {
    $q = $pdo->prepare('SELECT COUNT(id_c) FROM comment WHERE parent_message = ? AND parent_comment = ?') ;
    $q->execute([$id_m, $parent_comm]) ;
    $total = $q->fetch()[0] ;
    if ($total == NULL) exit() ;

    $q = $pdo->prepare('SELECT id_c, author, parent_message, content, date_published, date_edited, note FROM comment WHERE parent_message = ? AND parent_comment = ?') ;
    $q->execute([$id_m, $parent_comm]) ;
    $comments = $q->fetchAll() ;
    for ($k = 0 ; $k < $total ; ++$k) {
        $author_query = $pdo->prepare('SELECT username FROM users WHERE id_u = ?') ;
        $author_query->execute([$comments[$k]['author']]) ;
        $author = $author_query->fetch()[0] ;
        displayComment($k, $indent, $comments[$k], $pdo) ;
    }
}

function commentModal ($comment) {
?>
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
<?php
}
?>