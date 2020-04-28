<!DOCTYPE html>
<html>
  <?php
  include('includes/head.php') ;
  $page_limit = 10 ;
  $exists = 0 ;
  ?>

  <body class="d-flex vh-100 flex-column justify-content-between" onload="markdown()">
      <?php include('includes/header.php') ; ?>
      <main role="main" class="container">
        <?php
        if (!isset($_GET['post']) || is_null($_GET['post'])) {
          include('includes/nothing.php')  ;
        } else {
          if (isset($_GET['error'])) {
            if ($_GET['error'] == 'notset') $error = 'Veuillez remplir les champs' ;
            else if ($_GET['error'] == 'original' || $_GET['error'] == 'exists') $error = 'Cet article existe déjà dans cette langue' ;
            else $error = '' ;
            ?>
            <div class="alert alert-danger"><?= $error ; ?></div>
        <?php
          }
        ?>
        <section class="jumbotron">

<!-- The $_GET['post'] isn't set -->
        <?php
        $message_id = htmlspecialchars($_GET['post']) ;
        $query = $pdo->prepare('SELECT title, content, default_language FROM message WHERE id_m = ?') ;
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
        <?php } else { ?>
        <small class="text-muted">
           <a href="/article/?post=<?= $message_id ; ?>">« Retour au message d'origine</a>
        </small>
        <h1><?= $message['title'] ; ?></h1>
        <hr>
        <div class="markdown"><?= $message['content'] ; ?></div>
      </section>

<!-- Translation form -->
      <section class="jumbotron">
        <?php include('includes/blog/translate_form.php') ; ?>
        <?php } ?>
      </section>
<?php } ?>
    </main>
    <?php include('includes/footer.php') ; ?>


    <script src="/scripts/markdown.js" charset="utf-8"></script>
    <script src="/scripts/blog.js" charset="utf-8"></script>
  </body>
</html>
