<!DOCTYPE html>
<html>
    <?php
        include('includes/head.php') ;
        $page_limit = 10 ;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
    ?>

    <body class="d-flex vh-100 flex-column justify-content-between">
        <?php include('includes/header.php') ; ?>
        <main role="main" class="container">
            <?php
            if (!isset($_GET['post']) || is_null($_GET['post'])) {
            ?>
<!-- The $_GET['post'] isn't set -->
            <section class="jumbotron">
                <h1>Whoups, there's nothing here !</h1>
                <hr>
                <a href="index"><button type="button" class="btn btn-primary">Go back to home page</button></a>
            </section>
            <?php
            } else {
                $message_id = strip_tags($_GET['post']) ;
                $query = $pdo->prepare('SELECT COUNT(*) FROM message WHERE id_m = ?') ;
                $query->execute([$message_id]) ;
                $exists = $query->fetch()[0] ;
                if ($exists == 0) {
                ?>
<!-- The $_GET['post'] is set but no matching article in DB -->
                <section class="jumbotron">
                    <h1>Whoups, this article doesn't exist !</h1>
                    <hr>
                    <a href="index"><button type="button" class="btn btn-primary">Go back to home page</button></a>
                </section>
                <?php
                } else {
                    $query = $pdo->prepare('SELECT * FROM message WHERE id_m = ?') ;
                    $query->execute([$message_id]) ;
                    $message = $query->fetchAll()[0] ;
                    $author_query = $pdo->prepare('SELECT username FROM users WHERE id_user = ?') ;
                    $author_query->execute([$message['author']]) ;
                    $author = $author_query->fetch()[0] ;
            ?>
<!-- Article display -->
            <section class="jumbotron">
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
                $query = $pdo->prepare('SELECT COUNT(*) FROM comment WHERE parent_message = ? AND parent_comment IS NULL') ;
                $query->execute([$message_id]) ;
                $elements = $query->fetch()[0] ;
                if ($elements == 0) {
                    echo 'Would you like to <a href="#">comment</a> on this ?' ;
                } else {
                    $query = $pdo->prepare('SELECT * FROM comment WHERE parent_message = ? AND parent_comment IS NULL') ;
                    $query->execute([$message_id]) ;
                    $comments = $query->fetchAll() ;

                    function displayComment ($comm_index, $increment, $author, $comment, $pdo) {
                    ?>
                    <div class="container border-left border-dark mr-3 mb-3 p-3" style="<?php echo 'margin-left: ' . $increment . 'rem'?>">
                        <!-- get image from user -->
                        <header>
                            <h6><?php echo $author ; ?></h6>
                            <small class="text-muted">
                                <?php
                                echo "Published on " . $comment['date_published'] ;
                                if ($comment['date_edited'] != NULL) echo ", last edited on " . $comment['date_edited'] ;
                                ?>
                            </small>
                        </header>
                        <p><?php echo $comment['content'] ; ?></p>
                        <small class="text-muted"><a data-toggle="collapse" href="#collapseResp" aria-expanded="false" aria-controls="collapseResp">Répondre</a></small>

                        <div class="collapse" id="collapseResp">
                            <div class="card card-body">
                                <form id="submit-response" method="post" action="includes/blog/new_response.php/">
                                    <div class="form-group">
                                        <textarea type="text" class="form-control form-control-lg" id="content" name="content"></textarea>
                                    </div>
                                    <div class="collapse-footer">
                                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="collapse">Annuler</button>
                                        <button type="submit" class="btn btn-primary btn-sm" form="submit-response">Publier</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <hr>
                        <?php
                        getRelatedComments($comment['id_c'], $comment['parent_message'], $pdo, $increment) ;
                        ?>
                    </div>

                    <?php
                    }
                    function getRelatedComments ($parent_comm, $id_m, $pdo, $indent) {
                        $q = $pdo->prepare('SELECT COUNT(*) FROM comment WHERE parent_message = ? AND parent_comment = ?') ;
                        $q->execute([$id_m, $parent_comm]) ;
                        $total = $q->fetch()[0] ;
                        if ($total == NULL) exit() ;

                        $q = $pdo->prepare('SELECT * FROM comment WHERE parent_message = ? AND parent_comment = ?') ;
                        $q->execute([$id_m, $parent_comm]) ;
                        $comments = $q->fetchAll() ;
                        for ($k = 0 ; $k < $total ; ++$k) {
                            $author_query = $pdo->prepare('SELECT username FROM users WHERE id_user = ?') ;
                            $author_query->execute([$comments[$k]['author']]) ;
                            $author = $author_query->fetch()[0] ;
                            displayComment($k, $indent, $author, $comments[$k], $pdo) ;
                        }
                    }

                    $offset = $page_limit * ($page - 1) ;
                    for ($i = $offset ; $i < $offset + $page_limit && $i < $elements ; $i++) {
                        $author_query = $pdo->prepare('SELECT username FROM users WHERE id_user = ?') ;
                        $author_query->execute([$comments[$i]['author']]) ;
                        $author = $author_query->fetch()[0] ;
                        $increment = 1 ;
                        displayComment($i, $increment, $author, $comments[$i], $pdo) ;
                    }
                    ?>
                    <button type="button" class="btn btn-primary">Comment</button>
            <?php
                }
            }
            ?>
            </section>
        </main>
        <?php include('includes/footer.php') ; ?>
    </body>
</html>