<!DOCTYPE html>
<html>
    <?php
        $page_name = 'Blog';
        include('includes/head.php');
        $page_limit = 4 ;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
    ?>

    <body class="d-flex vh-100 flex-column justify-content-between">
        <?php include('includes/header.php'); ?>

        <main role="main" class="container">
          <?php if (isset($_SESSION['userid'])) { ; ?>
          <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#blogPostModal">Publier un nouvel article</button>
          <?php include('includes/blog/new_post.php'); ?>
        <?php } else { ?>
          <div class="alert alert-info float-right">Vous devez être connecté pour publier un article.</div>
        <?php } ?>

          <h2>Blog</h2>
          <hr>
          <?php include('includes/blog/page_nav.php') ; ?>

          <?php
          $query = $pdo->prepare('SELECT COUNT(id_m) FROM message WHERE category = ?') ;
          $query->execute([$page_name]) ;
          $elements = $query->fetch()[0] ;

          $query = $pdo->prepare('SELECT id_m, author title, content, date_published, username, icon, file_name FROM message
            LEFT JOIN users ON id_u = author
            LEFT JOIN language ON default_language = lang
            LEFT JOIN file ON message = id_m
            WHERE category = ?
            ORDER BY date_published, latest_c DESC') ;
          $query->execute([$page_name]) ;
          $messages = $query->fetchAll() ;

          $offset = $page_limit * ($page - 1) ;
          for ($i = $offset ; $i < $offset + $page_limit && $i < $elements ; ++$i) {
              $article = $messages[$i] ;
              $query = $pdo->prepare('SELECT COUNT(user) FROM vote_message WHERE message = ?') ;
              $query->execute([$article['id_m']]) ;
              $mark = $query->fetch()[0] ;
          ?>
          <section class="card mb-3" style="max-width: width;">
              <div class="row no-gutters">
                  <aside class="col-md-4">
                      <img src="
                      <?php if(!empty($article['file_name'])) echo 'images/' . $article['file_name'] ;
                      else echo 'https://www.meetech.ovh/images/logov4.svg' ; ?>" alt="Image article" style="max-width: 200px; max-height: 200px;">
                  </aside>
                  <article class="col-md-8">
                      <div class="card-body">
                          <div class="float-right">
                              <span class="badge badge-pill badge-success"><?= $mark ; ?></span>
                              <span class="badge badge-pill badge-danger">!</span>
                          </div>
                          <h5 class="card-title">
                            <span><?= $article['icon'] ?></span>
                            <a href="/article.php?post=<?= $article['id_m'] ; ?>"><?= $article['title'] ?></a>
                          </h5>
                          <p class="card-text">
                            <?= substr($article['content'], 0, 270) .'…' ; ?>
                            <a href="/article.php?post=<?= $article['id_m'] ; ?>"> » Continue reading</a>
                          </p>
                          <p class="card-text">
                            <?php $dp = new DateTime($article['date_edited']) ; ?>
                            <small class="text-muted">Publié le <?= $dp->format('d m Y à H:i') ; ?> by <a href="/user/?id=<?= $article['author'] ; ?>"><?= $article['username'] ; ?></a></small>
                          </p>
                      </div>
                  </article>
              </div>
          </section>
          <?php
          } ;
          include('includes/blog/page_nav.php') ;
          ?>
      </main>

      <?php include('includes/footer.php'); ?>
    </body>
</html>
