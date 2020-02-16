<header class="mb-4">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="/images/logov4.svg" style="height: 24px;">
                meeTech
            </a>
            <button class=" navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Configurateur
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">Composants</a>
                            <a class="dropdown-item" href="#">Configurateur</a>
                            <a class="dropdown-item" href="#">Comparateur</a>
                        </div>
                    </li>
                    <li class="nav-item <?php if ($_SERVER['REQUEST_URI'] == '/blog/') echo 'active'; ?>">
                        <a class="nav-link" href="/blog/">Blog</a>
                    </li>
                    <li class="nav-item <?php if ($_SERVER['REQUEST_URI'] == '/forum/') echo 'active'; ?>">
                        <a class="nav-link" href="/forum/">Forum</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Utilisateur
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">Profil</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Créer un compte</a>
                        </div>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="search" placeholder="Rechercher" aria-label="Rechercher">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Rechercher</button>
                </form>
            </div>
        </div>
    </nav>
</header>