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
            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#blogPostModal">Publier un nouvel article</button>
            <div class="modal fade bd-example-modal-xl" id="blogPostModal" tabindex="-1" role="dialog" aria-labelledby="blogPostModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="blogPostModalTitle">Nouvelle publication</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?php include('includes/blog/new_post.php'); ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary" form="submit-component">Publier</button>
                        </div>
                    </div>
                </div>
            </div>

            <h2>Blog</h2>

            <hr>
            <?php include('includes/blog/page_nav.php') ; ?>

            <?php
            $query = $pdo->prepare('SELECT COUNT(*) FROM message WHERE category = ?') ;
            $query->execute([$page_name]) ;
            $elements = $query->fetch()[0] ;

            $query = $pdo->prepare('SELECT * FROM message WHERE category = ? ORDER BY date_published DESC') ;
            $query->execute([$page_name]) ;
            $messages = $query->fetchAll() ;

            $offset = $page_limit * ($page - 1) ;
            for ($i = $offset ; $i < $offset + $page_limit && $i < $elements ; $i++) {
            ?>
            <section class="card mb-3" style="max-width: width;">
                <div class="row no-gutters">
                    <aside class="col-md-4">
                        <img src="images/logov2.svg" class="card-img" alt="..."> <!--Ajouter une recherche d'image en f(x) de l'article -->
                    </aside>
                    <article class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><a href=<?php echo '"article?post=' . $messages[$i]['id_m'] . '"' ; ?>><?php echo $messages[$i]['title'] ?></a></h5>
                            <p class="card-text">
                                <?php echo $messages[$i]['content'] ?> <!--Couper le texte à un nb de caractères donnés via un script JS-->
                                <a href=<?php echo '"article?post=' . $messages[$i]['id_m'] . '"' ; ?>>» Continue reading</a>
                            </p>
                            <p class="card-text">
                                <small class="text-muted">Published on <?php echo $messages[$i]['date_published'] ?> by 
                                    <?php
                                    $auth_query = $pdo->prepare('SELECT username FROM users WHERE id_user = ?') ;
                                    $auth_query->execute([$messages[$i]['author']]) ;
                                    $author = $auth_query->fetch()[0] ;
                                    echo '<a href="#">' . $author . '</a>';
                                    ?>
                                </small>
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