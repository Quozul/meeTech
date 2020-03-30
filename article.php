<!DOCTYPE html>
<html>
    <?php
        include('includes/head.php') ;
        include('includes/author_query.php') ;

        include('includes/blog/display_c.php') ;
        include('includes/blog/get_related_c.php') ;
        include('includes/blog/comment_modal.php') ;

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
              $query = $pdo->prepare('SELECT COUNT(id_m) FROM message WHERE id_m = ?') ;
              $query->execute([$message_id]) ;
              $exists = $query->fetch()[0] ;
              if ($exists == 0) {
              ?>

<!-- The $_GET['post'] is set but no matching article in DB -->
              <h1>Whoups, this article doesn't exist !</h1>
              <hr>
              <a href="blog/" type="button" class="btn btn-primary">Go to Blog</a>
              <a href="forum/" type="button" class="btn btn-primary">Go to Forum</a>

<!-- Valid article display -->
              <?php
              } else {
                $query = $pdo->prepare('SELECT author, title, content, date_published, date_edited, default_language, note, category FROM message WHERE id_m = ?') ;
                $query->execute([$message_id]) ;
                $message = $query->fetchAll()[0] ;
                $author = author_query($message['author'], $pdo) ;
                include('includes/blog/edit_modal.php') ;
              ?>
              <h1><?php echo $message['title'] ; ?></h1>
              <?php if (isset($_SESSION['userid']) && $_SESSION['userid'] == $message['author']) { ?>
              <div class="float-right">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#editModal">Éditer</button>
                <a href="actions/blog/drop_article.php?post=<?php echo $message['id_m'] ; ?>" type="button" class="btn btn-outline-dark btn-sm">Supprimer</a>
                <a href="actions/blog/report_article.php?post=<?php echo $message['id_m'] ; ?>" type="button" class="btn btn-outline-danger btn-sm">Signaler</a>
              </div>
              <?php } ?>
              <small class="text-muted">
                Published on <?php echo $message['date_published'] ?> by
                <?php echo '<a href="#">' . $author . '</a>' ; ?>
              </small>
              <hr>

              <img src="..." class="rounded float-left" alt="...">
              <div class="markdown"><?php echo $message['content'] ; ?></div>
              <button type="button" class="btn btn-success">+ <?php echo $message['note'] ; ?></button>
            <?php
              }
            }
            ?>
            </section>

            <?php
            if (isset($exists) && $exists != 0 && !is_null($message[0])) {
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


        <script src="scripts/markdown.js" charset="utf-8"></script>
    </body>
</html>
