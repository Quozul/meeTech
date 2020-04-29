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
        <?php
        if (isset($_GET['error'])) {
          if ($_GET['error'] == 'file1') $error = 'Le format de l\'image n\'est pas accepté' ;
          else if ($_GET['error'] == 'file2') $error = 'L\'image est trop volumineuse' ;
          else $error = '' ;
          ?>
          <div class="alert alert-danger"><?= $error ; ?></div>
          <?php
        }

        if (!isset($_GET['post']) || is_null($_GET['post'])) {
          include('includes/nothing.php')  ;
        } else {
        ?>
        <section class="jumbotron">

<!-- The $_GET['post'] isn't set -->
        <?php
        $message_id = htmlspecialchars($_GET['post']) ;
        $query = $pdo->prepare('SELECT username, avatar, author, title, content, date_published, date_edited, signaled,
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
        <?php
        $query = $pdo->query('SELECT name FROM category') ;
        $categories = $query->fetchAll() ;
        foreach ($categories as $cat) {
        ?>
        <a href="/community/?cat=<?= $cat['name'] ; ?>" type="button" class="btn btn-primary">Go to <?= ucfirst($cat['name']) ; ?></a>
        <?php } ?>

<!-- Valid article display -->
        <?php
        } else {
          include('includes/blog/edit_modal.php') ;
        ?>
        <small class="text-muted">
           <a href="/community/?cat=<?= $message['category'] ; ?>">« Retour au <?= $message['category'] ; ?></a>
        </small>
        <h1 id="article-title"><?= $message['title'] ; ?></h1>

        <div class="float-right">
          <span onclick="getTranslation(<?= $message_id ; ?>, '<?= $message['default_language']; ?>')"><?= $message['icon'] ; ?></span>
          <?php
          $stmt = $pdo->prepare('SELECT language, icon, label FROM translation
            LEFT JOIN language ON lang = language
            WHERE original_message = ?') ;
          $stmt->execute([$message_id]) ;
          $translations = $stmt->fetchAll() ;
          foreach ($translations as $t) {
          ?>
          <span onclick="getTranslation(<?= $message_id ; ?>, '<?= $t['language']; ?>')"><?= $t['icon'] ; ?></span>
          <?php
          }
          if (isset($_SESSION['userid'])) {
            if ($_SESSION['userid'] == $message['author']) {
          ?>
          <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#editModal">Éditer</button>
          <?php
            }
            if ($message['signaled'] == false) { ?>
              <button class="btn btn-outline-danger btn-sm" onclick="reportArticle(<?= $message_id ; ?>)">Signaler</button>
            <?php } else { ?>
              <div class="btn btn-warning btn-sm">Article signalé</div>
            <?php } ?>
            <a class="btn btn-outline-primary btn-sm" href="/translate/?post=<?= $message_id ; ?>">Traduire</a>
          <?php } ?>
        </div>

        <img src="/uploads/<?= $message['avatar'] ; ?>" alt="<?= $message['username'] ; ?>'s profile picture" class="mt-avatar float-left" style="max-width: 32px; max-height: 32px;">
        <small class="text-muted">
          <?php
          $dp = new DateTime($message['date_published']) ;
          $de = new DateTime($message['date_edited']) ;
          ?>
          Publié le <?= $dp->format('d/m/Y à H:i') ; ?> par
          <a href="/user/?id=<?= $message['author'] ; ?>"><?= $message['username'] ; ?></a>
          <?php if ($message['date_edited'] != NULL) echo ", dernière édition le " . $de->format('d/m/Y à H:i') ; ?>
          .
        </small>
        <hr>

        <?php if (!empty($message['file_name'])) { ?>
        <a href="/uploads/<?= $message['file_name'] ; ?>" target="_blank"><img src="/uploads/<?= $message['file_name'] ; ?>" class="rounded float-left mb-3 mr-3" alt="Image of article <?= $message_id ; ?>" style="max-width:250px;max-height:250px;"></a>
        <?php } ?>
        <?php if ($_SESSION['userid'] == $message['author']) { ?>
          <form enctype="multipart/form-data" method="post" action="/actions/blog/edit_image/?post=<?= $message_id ; ?>">
            <label for="image">Image</label>
            <input type="file" class="form-control-file" id="image" name="image">
            <small class="text-muted">Taille maximale : 5Mo</small>
            <input type="submit" value="Envoyer" class="btn btn-primary">
          </form>
        <?php } ?>
        <div style="min-height: 250px;">
          <div class="markdown" id="article-content"><?= $message['content'] ; ?></div>
          <button id="articleMark" type="button" class="btn btn-success" onclick="markArticle()"></button>
        </div>
        <script type="text/javascript">
          let article = <?= $message_id ; ?> ;
          let user = <?php  if (isset($_SESSION['userid'])) echo $_SESSION['userid'] ;
                            else echo '0' ;
                     ?> ;
          const voteButton = document.getElementById('articleMark') ;
          getArticleMark() ;

          function markArticle() {
            if (user != 0) {
              let request = new XMLHttpRequest() ;
              request.open('POST', '/actions/blog/mark_article/') ;
              request.onreadystatechange = function() {
                if (request.readyState === 4) {
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
            let request = new XMLHttpRequest() ;
            request.open('GET', '/includes/blog/get_article_mark/?article=' + article) ;
            request.onreadystatechange = function() {
              if (request.readyState === 4) {
                voteButton.innerHTML = '+ ' +  request.responseText ;
              }
            };
            request.send() ;
          }
        </script>
      </section>

<!-- Comments display -->
      <section class="jumbotron">
          <?php if (isset($_SESSION['userid']) && !empty($_SESSION['userid'])) { ?>
            <div class="alert alert-info">Les commentaires sont personnalisables en <a href="https://www.markdownguide.org/basic-syntax/" target="_blank">markdown</a></div>
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
      <?php } ?>

        <script type="text/javascript" charset="utf-8">
          getComments() ;

          function getComments() {
            const commentsDiv = document.getElementById('comments') ;
            let request = new XMLHttpRequest() ;
            request.open('POST', '/includes/blog/comments/') ;
            request.onreadystatechange = function() {
              if (request.readyState === 4) {
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
            request.open('POST', '/actions/blog/add_comment/') ;
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
      <?php } ?>
    </main>
    <?php include('includes/footer.php') ; ?>


    <script src="/scripts/markdown.js" charset="utf-8"></script>
    <script src="/scripts/blog.js" charset="utf-8"></script>
  </body>
</html>
