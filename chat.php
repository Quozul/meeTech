<!DOCTYPE html>
<html>

<?php include('includes/head.php'); ?>

<body>
    <?php include('includes/header.php');

    $stmt = $pdo->prepare('SELECT id_c, name FROM channel INNER JOIN recipient ON channel = id_c WHERE author = ?');
    $stmt->execute([$_SESSION['userid']]);
    $result = $stmt->fetchAll();
    ?>
    <main role="main" class="container">
        <div class="jumbotron">
            <div class="row">
                <div class="col-3">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <?php foreach ($result as $category) { ?>
                            <a class="nav-link" id="v-pills-<?= $category['id_c']; ?>-tab" data-toggle="pill" href="#v-pills-<?= $category['id_c']; ?>" role="tab" aria-controls="v-pills-<?= $category['id_c']; ?>" aria-selected="true" onclick="getChat(<?= $category['id_c']; ?>)"><?= $category['name']; ?></a>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-9">
                    <div class="tab-content" id="v-pills-tabContent">
                        <?php foreach ($result as $category) { ?>
                            <div class="tab-pane fade show" id="v-pills-<?= $category['id_c']; ?>" role="tabpanel" aria-labelledby="v-pills-<?= $category['id_c']; ?>-tab">...</div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <hr>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createChanModal">Créer un salon de discussion</button>
            <?php include('actions/chat/create_chan_form.php'); ?>
        </div>
    </main>

</body>
<script src="/scripts/chat.js" charset="utf-8"></script>
<script src="/scripts/main.js" charset="utf-8"></script>

</html>