<!DOCTYPE html>
<html>
  <?php
      include('includes/head.php') ;

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
            $query = $pdo->prepare('SELECT username, author, title, content, date_published, date_edited,
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
            <h1><?= $message['title'] ; ?></h1>

            <div class="float-right">
              <span><?= $message['icon'] ; ?></span>
              <?php if (isset($_SESSION['userid']) && $_SESSION['userid'] == $message['author']) { ?>
              <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#editModal">Éditer</button>
              <?php } ?>
              <a href="/actions/blog/report_article.php?post=<?= $message_id ; ?>" type="button" class="btn btn-outline-danger btn-sm">Signaler</a>
            </div>

            <small class="text-muted">
              Published on <?= $message['date_published'] ?> by
              <a href="user/?id=<?= $message['author'] ; ?>"><?= $message['username'] ; ?></a>
            </small>
            <hr>

            <?php if (!empty($message['file_name'])) { ?>
            <img src="images/<?= $message['file_name'] ; ?>" class="rounded float-left mb-3 mr-3" alt="Image of article <?= $message_id ; ?>" style="max-width:250px;max-height:250px;">
            <?php } ?>

            <div class="markdown"><?= $message['content'] ; ?></div>

            <button id="articleMark" type="button" class="btn btn-success" onclick="markArticle()"></button>

            <script type="text/javascript">
            let article = <?php echo $message_id ; ?> ;
            let user = <?php  if (isset($_SESSION['userid'])) echo $_SESSION['userid'] ;
                              else echo '0' ;
                       ?> ;
            const voteButton = document.getElementById('articleMark') ;
            getArticleMark() ;

            function markArticle() {
              if (user != 0) {
                const request = new XMLHttpRequest() ;
                request.open('POST', '../actions/blog/mark_article/') ;
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
                  <form>
                      <div class="form-group">
                          <label for="comment" id="comment-label">Commentaire</label>
                          <textarea class="form-control" id="comment" name="comment" placeholder="Écrivez un commentaire..."></textarea>
                      </div>
                      <button type="button" onclick="submit_comment(<?php echo $article; ?>);" class="btn btn-primary">Poster</button>
                  </form>
              <?php } else { ?>
                  <div class="alert alert-info">Vous devez être connecté pour poster un commentaire.</div>
              <?php } ?>
              <hr>
              <div id="comments"><?php include('includes/blog/comments.php') ; ?></div>

              <script>
                  let answers = null;

                  function update_comments() {
                      const url_params = new URLSearchParams(window.location.search);
                      const article = url_params.get('post');
                      getHtmlContent('../includes/blog/comments.php', `post=${article}`).then((res) => {
                          document.getElementById('comments').innerHTML = res;
                      });
                  }

                  function submit_comment() {
                      let query = `id=${article}&comment=${document.getElementById('comment').value}`;

                      const answers = comment_get_answer();
                      if (answers != null)
                          query += `&answers=${answers}`;

                      request('/actions/hardware/submit_comment.php', query).then((e) => {
                          console.log(e.resonse);

                          update_comments();

                          document.getElementById('comment').value = '';
                      });
                  }

                  function comment_get_answer() {
                      return answers;
                  }

                  function aswer_comment(comment_id) {
                      const comments = document.getElementsByClassName('comment');
                      const comment = document.getElementById(`comment-${comment_id}`);
                      const comment_label = document.getElementById('comment-label');

                      if (!comment.classList.contains('bg-light')) {

                          for (const key in comments)
                              if (comments.hasOwnProperty(key)) {
                                  const element = comments[key];
                                  if (element.classList.contains('bg-light'))
                                      element.classList.remove('bg-light');
                              }

                          comment.classList.add('bg-light');
                          answers = comment_id;

                          comment_label.innerHTML = `Répondre à ${comment.getElementsByClassName('comment-author')[0].innerHTML}`;
                      } else {
                          comment.classList.remove('bg-light');
                          comment_label.innerHTML = `Commentaire`;
                          answers = null;
                      }
                  }

                  function remove_comment(comment_id) {
                      let query = `id=${comment_id}`;

                      request('/actions/hardware/delete_comment.php', query).then((e) => {
                          console.log(e.resonse);
                          update_comments();
                      });
                  }

                  function page_update_component() {
                      document.getElementById('close-edit-component-modal').click();
                      window.location.reload();
                  }

                  update_comments();
              </script>
          <?php } } ?>
          </section>
      </main>
      <?php include('includes/footer.php') ; ?>

      <script src="../scripts/markdown.js" charset="utf-8"></script>
      <script src="../scripts/blogVerifications.js" charset="utf-8"></script>
  </body>
</html>
