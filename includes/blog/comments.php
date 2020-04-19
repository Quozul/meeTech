<?php
include_once('../../config.php') ;
if (isset($_POST['post']) && !empty($_POST['post'])) {
  $article = $_POST['post'] ;

  $sth = $pdo->prepare('SELECT id_c, author, avatar, username, content, date_published, date_edited FROM comment
    LEFT JOIN users ON id_u = author
    WHERE parent_message = ? AND parent_comment = 0 ORDER BY date_published DESC') ;
  $sth->execute([$article]) ;
  $result = $sth->fetchAll() ;

  function print_comment($pdo, $comment, $padding)
  {
      // get children comments
      $req = $pdo->prepare('SELECT id_c, author, avatar, username, content, date_published, date_edited FROM comment
        LEFT JOIN users ON id_u = author
        WHERE parent_comment = ? ORDER BY date_published DESC') ;
      $req->execute([$comment['id_c']]) ;
      $child_comments = $req->fetchAll() ;
  ?>

      <div class="border-left border-dark p-1 mb-2 comment" style="margin-left: <?= $padding ; ?>px" id="comment-<?= $comment['id_c'] ; ?>">
          <div>
            <?php if (isset($_SESSION['userid']) && $comment['content'] != '*Commentaire supprimé*') { ?>
              <div class="float-right">
                <?php if ($comment['author'] == $_SESSION['userid']) { ?>
                  <button class="badge badge-danger btn-sm mr-2" onclick="dropComment(<?= $comment['id_c'] ; ?>)">Supprimer</button>
                  <button class="badge badge-secondary btn-sm mr-2" onclick="editComment(<?= $comment['id_c'] ; ?>)">Modifier</button>
                <?php } ?>
                <button class="badge badge-danger mr-2">Signaler</button>
              </div>
            <?php } ?>

            <img src="/uploads/<?= $comment['avatar'] ; ?>" alt="<?= $comment['author'] ; ?>'s' profile picture" style="width:32px; height:32px;" class="mt-avatar float-left">
            <h7 class="d-inline comment-author"><a href="/user/?id=<?= $comment['author'] ; ?>"><?= $comment['username'] ; ?></a></h7>

            <small class="text-muted">
                <?php
                $dp = new DateTime($comment['date_published']) ;
                $de = new DateTime($comment['date_edited']) ;
                echo "Publié le " . $dp->format('d m yy à H:i') ;
                if ($comment['date_edited'] != NULL) echo ", dernière édition le " . $dp->format('d m yy à H:i') ;
                ?>
            </small>

            <div class="mb-0 markdown" id="comment<?= $comment['id_c'] ; ?>"><p><?= $comment['content'] ; ?></p></div>

            <div class="collapse" id="collapseEdit<?php echo $comment['id_c'] ; ?>">
              <textarea type="text" class="form-control form-control" id="editContent<?php echo $comment['id_c'] ; ?>" name="editComment"><?= $comment['content'] ; ?></textarea>
              <div class="collapse-footer">
                <button class="btn btn-secondary btn-sm" onclick="getComments()">Annuler</button>
                <button class="btn btn-primary btn-sm" onclick="submitModif(<?php echo $comment['id_c'] ; ?>)">Modifier</button>
              </div>
            </div>

            <?php if (isset($_SESSION['userid'])) { ?>
            <small class="badge badge-primary ml-5" data-toggle="collapse" href="#collapseResp<?php echo $comment['id_c'] ; ?>" aria-expanded="false" aria-controls="collapseResp<?php echo $comment['id_c'] ; ?>">Répondre</small>
            <?php } ?>
            <div class="collapse" id="collapseResp<?php echo $comment['id_c'] ; ?>">
              <hr>
              <div class="form-group">
                <textarea type="text" class="form-control form-control" id="collapseContent<?php echo $comment['id_c'] ; ?>" name="collapseContent"></textarea>
              </div>
              <div class="collapse-footer">
                <button class="btn btn-primary btn-sm" onclick="submitComment(<?php echo $comment['id_c'] ; ?>)">Poster</button>
              </div>
            </div>
          </div>
      </div>
      <hr>

      <?php
      if (count($child_comments) > 0)
          foreach ($child_comments as $key => $child) {
              print_comment($pdo, $child, $padding + 25) ;
          }
  }

  if (count($result)) {
       foreach ($result as $key => $comment) {
          print_comment($pdo, $comment, 0) ;
      }
  } else { ?>
       <p class="lead">Aucun commentaire pour cet article, soyez le premier à laisser un avis !</p>
  <?php
  }
} else {
  echo '-1' ;
  return ;
}
?>
