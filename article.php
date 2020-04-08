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
                $query = $pdo->prepare('SELECT author, title, content, date_published, date_edited, default_language, category FROM message WHERE id_m = ?') ;
                $query->execute([$message_id]) ;
                $message = $query->fetchAll()[0] ;
                $author = author_query($message['author'], $pdo) ;
                include('includes/blog/edit_modal.php') ;
              ?>
              <h1><?= $message['title'] ; ?></h1>

              <div class="float-right">
                <?php if (isset($_SESSION['userid']) && $_SESSION['userid'] == $message['author']) { ?>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#editModal">Éditer</button>
                <?php } ?>
                <a href="actions/blog/report_article.php?post=<?= $message_id ; ?>" type="button" class="btn btn-outline-danger btn-sm">Signaler</a>
              </div>

              <small class="text-muted">
                Published on <?= $message['date_published'] ?> by
                <?= '<a href="#">' . $author . '</a>' ; ?>
              </small>
              <hr>

              <?php
              $query = $pdo->prepare('SELECT file_name FROM file WHERE message = ?') ;
              $query->execute([$message_id]) ;
              $image = $query->fetch() ;
              if (!empty($image)) {
              ?>
              <img src="assets/<?= $image['file_name'] ; ?>" class="rounded float-left mb-3 mr-3" alt="Image of article <?= $message_id ; ?>" style="max-width:250px;max-height:250px;">
              <?php } ?>

              <div class="markdown"><?= $message['content'] ; ?></div>

              <button id="articleMark" type="button" class="btn btn-success" <?php if (isset($result) && $result == true) echo 'disabled' ; ?> onclick="markArticle()"></button>

              <script type="text/javascript">
              let article = <?php echo $message_id ; ?> ;
              let user = <?php  if (isset($_SESSION['userid'])) echo $_SESSION['userid'] ;
                                else echo 'NULL' ;
                         ?> ;
              const voteButton = document.getElementById('articleMark') ;
              getArticleMark() ;

              function markArticle() {
                if (user != 'NULL') {
                  const request = new XMLHttpRequest() ;
                  request.open('POST', '/actions/blog/mark_article.php') ;
                  request.onreadystatechange = function() {
                    if (request.readyState === 4) {//event de fin de requête XMLHttpRequest
                      const success = parseInt(request.responseText);
                      if (success === 1) {
                        voteButton.disabled = 'disabled' ;
                        getArticleMark() ;
                      } else if (success === -1) {
                        alert("Vous devez être connecté pour voter pour un article.") ;
                      } else if (success === -3) {
                        alert("Vous avez déjà voté pour cet article.") ;
                      } else {
                        alert("Une erreur est survenue") ;
                      }
                    }
                  };
                  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                  request.send(`userid=${user}&message_id=${article}`);
                }
              }

              function getArticleMark() {
                const request = new XMLHttpRequest() ;
                request.open('POST', '/includes/blog/get_article_mark.php') ;
                request.onreadystatechange = function() {
                  if (request.readyState === 4) {//event de fin de requête XMLHttpRequest
                    voteButton.innerHTML = '+ ' +  request.responseText ;
                  }
                };
                request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                request.send(`message_id=${article}`);
              }
              </script>
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
                $query = $pdo->prepare('SELECT id_c, author, parent_message, content, date_published, date_edited FROM comment WHERE parent_message = ? AND parent_comment = ?') ;
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
        <script src="scripts/blogVerifications.js" charset="utf-8"></script>
    </body>
</html>
