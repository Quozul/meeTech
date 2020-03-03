<?php

if (isset($_SESSION['userid'])) {
    $sth = $pdo->prepare('SELECT username FROM users WHERE id_u = ? ');

    $sth->execute([$_SESSION['userid']]);

    $rec = $sth->fetchAll();
}

?>

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
                    <li class="nav-item <?php if ($_SERVER['REQUEST_URI'] == '/blog/') echo 'active'; ?>">
                        <a class="nav-link" href="/blog/">Blog</a>
                    </li>
                    <li class="nav-item <?php if ($_SERVER['REQUEST_URI'] == '/forum/') echo 'active'; ?>">
                        <a class="nav-link" href="/forum/">Forum</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" <?php if (isset($rec)) {
                                                echo 'href="/profile.php"';
                                            } else {
                                                echo 'data-toggle="modal" data-target="#sign_in_modal"';
                                            } ?>>
                            <?php if (isset($rec)) {
                                echo $rec[0]['username'];
                            } else {
                                echo 'Se connecter';
                            } ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" <?php if (!isset($rec)) {
                                                echo 'data-toggle="modal" data-target="#sign_up_modal"';
                                            } ?>>
                            <?php if (!isset($rec)) {
                                echo 'Créer un compte';
                            } ?></a>
                    </li>
                    <?php if (isset($rec)) { ?>

                        <li class="nav-item">
                            <a class="nav-link" onclick="request('/actions/profile/sign_out.php','').then(function(){document.location.reload();})">Se déconnecter</a>
                        </li>
                    <?php } ?>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="search" placeholder="Rechercher" aria-label="Rechercher">
                    <button class="btn btn-success my-2 my-sm-0" type="submit">Rechercher</button>
                </form>
            </div>
        </div>
    </nav>
</header>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/profile/sign_up_form.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/profile/sign_in_form.php'); ?>