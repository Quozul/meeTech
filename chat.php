<!DOCTYPE html>
<html>

<?php include('includes/head.php'); ?>

<body>
    <?php include('includes/header.php');
    if (!isset($_SESSION['userid'])) {
      include('includes/nothing.php') ;
    } else {

      $stmt = $pdo->prepare('SELECT id_c, name, notif FROM channel INNER JOIN recipient ON channel = id_c WHERE author = ?');
      $stmt->execute([$_SESSION['userid']]);
      $result = $stmt->fetchAll();
      ?>
      <main role="main" class="container" style="height: 100vh;">
          <div class="jumbotron">
              <div class="row">
                  <div class="col-3">
                      <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                          <?php foreach ($result as $chan) { ?>
                              <a class="nav-link" id="v-pills-<?= $chan['id_c']; ?>-tab" data-toggle="pill" href="#v-pills-<?= $chan['id_c']; ?>" role="tab"
                                aria-controls="v-pills-<?= $chan['id_c']; ?>" aria-selected="true" onclick="getChat(<?= $chan['id_c']; ?>, 0)">
                                <?= $chan['name']; ?>
                                <span class="badge <?= $chan['notif'] == 0 ? 'badge-success' : 'badge-danger' ; ?>" id="notifs-<?= $chan['id_c']; ?>"><?= $chan['notif'] ; ?></badge>
                              </a>
                          <?php } ?>
                      </div>
                  </div>
                  <div class="col-9">
                      <div class="tab-content" id="v-pills-tabContent">
                          <?php foreach ($result as $chan) { ?>
                              <div class="tab-pane fade show" id="v-pills-<?= $chan['id_c']; ?>" role="tabpanel" aria-labelledby="v-pills-<?= $chan['id_c']; ?>-tab">
                                  <div id="recipients-<?= $chan['id_c']; ?>"></div>
                                  <hr>
                                  <div class="float-right">
                                      <form method="post" class="mb-3 ml-3">
                                          <input class="form-control mb-2" type="text" name="add_user" id="add_user-<?= $chan['id_c']; ?>" placeholder="Utilisateur à ajouter">
                                          <button type="button" class="btn btn-success btn-sm" onclick="add_recipient(<?= $chan['id_c']; ?>)">Ajouter l'utilisateur</button>
                                      </form>
                                      <small class="alert" id="add_success-<?= $chan['id_c']; ?>"></small>
                                      <hr>
                                      <div>
                                          <button class="btn btn-danger btn-sm ml-3 mb-3" onclick="leaveChat(<?= $chan['id_c']; ?>)">Quitter le salon</button>
                                      </div>
                                  </div>
                                  <div id="display_pm-<?= $chan['id_c']; ?>" name="display_mp" style="max-height: 60vh; overflow: scroll; overflow-x: hidden;"></div>

                                  <form method="post">
                                      <div class="input-group mb-2">
                                          <textarea class="form-control" type="text" name="message" id="message-<?= $chan['id_c']; ?>" placeholder="Message"></textarea>
                                          <div class="input-group-append">
                                              <button type="button" class="btn btn-info" onclick="submitMessage(<?= $chan['id_c']; ?>)">Envoyer</button>
                                          </div>
                                      </div>
                                  </form>
                                  <div class="alert" id="size_mp-<?= $chan['id_c']; ?>"></div>
                              </div>
                          <?php } ?>
                      </div>
                  </div>
              </div>
              <hr>
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createChanModal">Créer un salon de discussion</button>
              <?php include('actions/chat/create_chan_form.php'); ?>
          </div>
      </main>
    <?php } ?>

</body>
<?php include('includes/footer.php') ?>
<script src="/scripts/markdown.js" charset="utf-8"></script>
<script src="/scripts/chat.js" charset="utf-8"></script>
<script src="/scripts/main.js" charset="utf-8"></script>

</html>
