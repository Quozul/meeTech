<?php if (isset($_SESSION['userid'])) {
    $sth = $pdo->prepare('SELECT username, COUNT(notif) AS notifs FROM users LEFT JOIN recipient ON id_u = author WHERE id_u = ?');
    $sth->execute([$_SESSION['userid']]);
    $rec = $sth->fetchAll();
}
$query = $pdo->query('SELECT name FROM category') ;
$categories = $query->fetchAll() ; ?>

<header class="mb-4">
    <nav class="navbar navbar-expand-lg <?php if (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false) echo 'mt-backoffice-color'; ?>">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="/images/logov4.svg" style="height: 24px;">
                meeTech
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Matériel
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/components/">Composants</a>
                            <a class="dropdown-item" href="/builder/">Configurateur</a>
                            <a class="dropdown-item" href="/comparator/">Comparateur</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Communauté
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown2">
                          <?php foreach($categories as $cat) { ?>
                          <a class="dropdown-item" href="/community/?cat=<?= $cat['name'] ; ?>"><?= $cat['name'] ; ?></a>
                          <?php } ?>
                        </div>
                    </li>
                    <?php if (isset($rec)) { ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Profil
                                <?php if ($rec[0]['notifs'] != 0) { ?>
                                <span class="badge badge-alert"><?= $rec[0]['notifs'] ; ?></span>
                                <?php } ?>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" class="nav-link" href="/profile/">
                                    <?php echo $rec[0]['username']; ?>
                                </a>
                                <a class="dropdown-item" href="/chat/">Messagerie privée</a>
                                <?php if ($rec[0]['notifs'] != 0) { ?>
                                <span class="badge badge-alert"><?= $rec[0]['notifs'] ; ?></span>
                                <?php } ?>
                            </div>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a href="" class="nav-link" data-toggle="modal" data-target="#sign_in_modal">
                                Se connecter
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (!isset($rec)) { ?>
                        <li class="nav-item">
                            <a href="" class="nav-link" data-toggle="modal" data-target="#sign_up_modal">
                                Créer un compte
                            </a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a href="" class="nav-link" onclick="request('/actions/profile/sign_out.php','').then(function(){document.location.reload();})">Se déconnecter</a>
                        </li>
                    <?php } ?>
                </ul>
                <div class="form-inline my-2 my-lg-0" id="search-form">
                    <input class="form-control mr-sm-2" type="search" placeholder="Rechercher" id="search-bar">
                    <button class="btn btn-success my-2 my-sm-0" type="button" id="search-button">Rechercher</button>
                </div>
            </div>
        </div>
    </nav>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/profile/sign_up_form.php'); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/profile/sign_in_form.php'); ?>

    <script src="/scripts/search_bar.js"></script>
</header>
