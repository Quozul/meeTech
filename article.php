<!DOCTYPE html>
<html>
  <?php
      include('includes/head.php') ;
      $page_limit = 10 ;
      $page = isset($_GET['page']) ? $_GET['page'] : 1;
      $exists = 0 ;
  ?>

  <body class="d-flex vh-100 flex-column justify-content-between" onload="markdown()">
      <?php include('includes/header.php') ; ?>
      <main role="main" class="container">
          <section class="jumbotron">

<!-- The $_GET['post'] isn't set -->
          <?php
          if (!isset($_GET['post']) || is_null($_GET['post'])) {
            include('includes/nothing.php')  ;
          } else {
            $message_id = strip_tags($_GET['post']) ;
            $query = $pdo->prepare('SELECT username, avatar, author, title, content, date_published, date_edited,
              default_language, icon, category, file_name FROM message
              LEFT JOIN users ON id_u = author
              LEFT JOIN file ON message = id_m
              LEFT JOIN language ON lang = default_language
              WHERE id_m = ?') ;
            $query->execute([$message_id]) ;
            $message = $query->fetch() ;
            if (!isset($message) || empty($message)) {
            ?>
<!-- The $_GET['post'] is set but no matching article in DB -->
            <h1>Whoups, this article doesn't exist !</h1>
            <hr>
            <a href="/blog/" type="button" class="btn btn-primary">Go to Blog</a>
            <a href="/forum/" type="button" class="btn btn-primary">Go to Forum</a>

<!-- Valid article display -->
            <?php
            } else {
              include('includes/blog/edit_modal.php') ;
            ?>
            <small class="text-muted">
               <a href="/<?= $message['category'] ; ?>/">« Retour au<?= $message['category'] ; ?></a>
            </small>
            <h1><?= $message['title'] ; ?></h1>

            <div class="float-right">
              <span><?= $message['icon'] ; ?></span>
              <?php if (isset($_SESSION['userid']) && $_SESSION['userid'] == $message['author']) { ?>
              <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#editModal">Éditer</button>
              <?php } ?>
              <a href="/actions/blog/report_article/?post=<?= $message_id ; ?>" type="button" class="btn btn-outline-danger btn-sm">Signaler</a>
            </div>

            <img src="/uploads/<?= $message['avatar'] ; ?>" alt="<?= $message['username'] ; ?>'s profile picture" class="mt-avatar float-left" style="max-width: 32px; max-height: 32px;">
            <small class="text-muted">
              <?php
              $dp = new DateTime($message['date_published']) ;
              $de = new DateTime($message['date_edited']) ;
              ?>
              Publié le <?= $dp->format('d m Y à H:i') ; ?> par
              <a href="/user/?id=<?= $message['author'] ; ?>"><?= $message['username'] ; ?></a>
              <?php if ($message['date_edited'] != NULL) echo ", dernière édition le " . $de->format('d m Y à H:i') ; ?>
              .
            </small>
            <hr>

            <?php if (!empty($message['file_name'])) { ?>
            <img src="images/<?= $message['file_name'] ; ?>" class="rounded float-left mb-3 mr-3" alt="Image of article <?= $message_id ; ?>" style="max-width:250px;max-height:250px;">
            <?php } ?>

            <div class="markdown"><?= $message['content'] ; ?></div>

            <button id="articleMark" type="button" class="btn btn-success" onclick="markArticle()"></button>

            <script type="text/javascript">
              let article = <?= $message_id ; ?> ;
              let user = <?php  if (isset($_SESSION['userid'])) echo $_SESSION['userid'] ;
                                else echo '0' ;
                         ?> ;
              const voteButton = document.getElementById('articleMark') ;
              getArticleMark() ;

              function markArticle() {
                if (user != 0) {
                  const request = new XMLHttpRequest() ;
                  request.open('PUT', '../actions/blog/mark_article/') ;
                  request.onreadystatechange = function() {
                    if (request.readyState === 4) {//event de fin de requête XMLHttpRequest
                      const success = parseInt(request.responseText);
                      if (success === 1) {
                        voteButton.disabled = 'disabled' ;
                        getArticleMark() ;
                      } else if (success === -1) {
                        alert("Vous devez être connecté pour voter pour un article.") ;
                      } else if (success === -2) {
                        alert("Ne jouez pas avec l'Ajax !") ;
                      } else if (success === -3) {
                        alert("Vous avez déjà voté pour cet article.") ;
                      } else {
                        alert("Une erreur est survenue") ;
                      }
                    }
                  };
                  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                  request.send(`message_id=${article}`);
                } else {
                  alert("Vous devez être connecté pour voter pour un article.") ;
                }
              }

              function getArticleMark() {
                const request = new XMLHttpRequest() ;
                request.open('POST', '../includes/blog/get_article_mark/') ;
                request.onreadystatechange = function() {
                  if (request.readyState === 4) {//event de fin de requête XMLHttpRequest
                    voteButton.innerHTML = '+ ' +  request.responseText ;
                  }
                };
                request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                request.send(`message_id=${article}`);
              }
            </script>
          </section>

<!-- Comments display -->
          <section class="jumbotron">
              <?php if (isset($_SESSION['userid']) && !empty($_SESSION['userid'])) { ?>
                <div class="form-group">
                    <label for="comment" id="comment-label">Commentaire</label>
                    <textarea class="form-control" id="collapseContent0" name="comment" placeholder="Écrivez un commentaire…"></textarea>
                </div>
                <button type="button" onclick="submitComment(0)" class="btn btn-primary">Poster</button>
              <?php } else { ?>
                  <div class="alert alert-info">Vous devez être connecté pour poster un commentaire.</div>
              <?php } ?>
              <hr>
              <div id="comments"></div>
          <?php
            }
          } ?>

            <script type="text/javascript" charset="utf-8">
              getComments() ;

              function getComments() {
                const commentsDiv = document.getElementById('comments') ;
                let request = new XMLHttpRequest() ;
                request.open('POST', '../includes/blog/comments/') ;
                request.onreadystatechange = function() {
                  if (request.readyState === 4) {//event de fin de requête XMLHttpRequest
                    if (request.responseText == -1) {
                      alert('Une erreur est survenue') ;
                    } else {
                      commentsDiv.innerHTML = request.responseText ;
                      markdown() ;
                    }
                  }
                };
                request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                request.send(`post=${article}`);
              }

              function submitComment(id) {
                const content = document.getElementById('collapseContent' + id).value ;
                const parent = id ;

                let request = new XMLHttpRequest() ;
                request.open('POST', '../actions/blog/add_comment/') ;
                request.onreadystatechange = function() {
                  if (request.readyState === 4) {
                    const success = parseInt(request.responseText);
                    if (success === 1) {
                      getComments() ;
                    }
                    else if (success === -2) alert("Vous devez être connecté pour publier un commentaire !") ;
                    else if (success === 0) alert("Erreur 0 !") ;
                    else alert("Une erreur est survenue") ;
                  }
                };
                request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                request.send(`parentMessage=${article}&commentContent=${content}&parentComment=${id}`);
              }
            </script>
          </section>
      </main>
      <?php include('includes/footer.php') ; ?>


      <script src="../scripts/markdown.js" charset="utf-8"></script>
      <script src="../scripts/blogVerifications.js" charset="utf-8"></script>
  </body>
</html>
