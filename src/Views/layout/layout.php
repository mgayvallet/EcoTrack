<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($title ?? 'EcoTrack') ?></title>
    <script>
        // Applique le thème avant le rendu pour éviter le flash.
        (function() {
            try {
                if (localStorage.getItem('theme') === 'dark') {
                    document.documentElement.setAttribute('data-theme', 'dark');
                }
            } catch (e) {}
        })();
    </script>
    <link rel="stylesheet" href="/style/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet" />
</head>

<body>
    <div class="layout">

        <header class="site-header">
            <a href="/" class="logo">
                <div class="logo-icon">
                    <img src="../assets/icons/logo.svg" alt="">
                </div>
                <span class="logo-text">EcoTrack</span>
            </a>

            <button type="button" class="nav-toggle" id="navToggle" aria-label="Menu" aria-expanded="false" aria-controls="siteNav">
                <span class="nav-toggle-bar"></span>
                <span class="nav-toggle-bar"></span>
                <span class="nav-toggle-bar"></span>
            </button>

            <nav class="site-nav" id="siteNav">
                <a href="/calculator">Calculateur</a>
                <a href="/challenge">Défis</a>
                <a href="/articles">Articles</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-menu" id="userMenu">
                        <button type="button" class="user-name" id="userMenuToggle" aria-haspopup="true" aria-expanded="false">
                            <?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>
                            <svg class="user-menu-caret" width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                        <div class="user-menu-popup" id="userMenuPopup">
                            <button type="button" class="user-menu-item" id="darkModeToggle">
                                <span>Mode sombre</span>
                                <span class="switch" aria-hidden="true">
                                    <span class="switch-knob">
                                        <img class="switch-icon switch-sun" src="/assets/icons/light.svg" alt="">
                                        <img class="switch-icon switch-moon" src="/assets/icons/dark.svg" alt="">
                                    </span>
                                </span>
                            </button>
                            <a href="/logout" class="user-menu-item user-menu-logout">Déconnexion</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="user-menu" id="userMenu">
                        <button type="button" class="user-name" id="userMenuToggle" aria-haspopup="true" aria-expanded="false">
                            <?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>
                            <svg class="user-menu-caret" width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                        <div class="user-menu-popup" id="userMenuPopup">
                            <button type="button" class="user-menu-item" id="darkModeToggle">
                                <span>Mode sombre</span>
                                <span class="switch" aria-hidden="true">
                                    <span class="switch-knob">
                                        <img class="switch-icon switch-sun" src="/assets/icons/light.svg" alt="">
                                        <img class="switch-icon switch-moon" src="/assets/icons/dark.svg" alt="">
                                    </span>
                                </span>
                            </button>
                            <a href="/login" class="btn-connexion">Connexion</a>
                        </div>
                    </div>
                <?php endif; ?>
            </nav>
        </header>

        <main class="site-main">
            <?= $content ?? '' ?>
        </main>

        <footer class="site-footer">
            <div class="footer-inner">
                <div class="footer-brand">
                    <a href="/" class="footer-logo">
                        <div class="logo-icon">
                            <img src="../assets/icons/logo.svg" alt="">
                        </div>
                        <span class="footer-logo-text">EcoTrack</span>
                    </a>
                    <p class="footer-tagline">Votre compagnon pour un mode de vie plus durable. Mesurez, apprenez et réduisez votre empreinte carbone au quotidien.</p>
                </div>

                <div class="footer-col">
                    <h4>Fonctionnalités</h4>
                    <ul>
                        <li><a href="/calculator">Calculateur</a></li>
                        <li><a href="/challenge">Défis</a></li>
                        <li><a href="/articles">Articles</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>À propos</h4>
                    <ul>
                        <li><a href="/contact">Nous contacter</a></li>
                    </ul>
                </div>
        </footer>

    </div>

    <script>
        // Menu de navigation mobile (hamburger)
        (function() {
            const navToggle = document.getElementById('navToggle');
            const siteNav = document.getElementById('siteNav');
            if (!navToggle || !siteNav) return;

            navToggle.addEventListener('click', function() {
                const open = siteNav.classList.toggle('open');
                navToggle.classList.toggle('open', open);
                navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            });

            siteNav.querySelectorAll('a').forEach(function(link) {
                link.addEventListener('click', function() {
                    siteNav.classList.remove('open');
                    navToggle.classList.remove('open');
                    navToggle.setAttribute('aria-expanded', 'false');
                });
            });
        })();

        (function() {
            const menu = document.getElementById('userMenu');
            if (!menu) return;

            const toggle = document.getElementById('userMenuToggle');
            const popup = document.getElementById('userMenuPopup');

            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                const open = menu.classList.toggle('open');
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            });

            document.addEventListener('click', function(e) {
                if (!menu.contains(e.target)) {
                    menu.classList.remove('open');
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    menu.classList.remove('open');
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });

            // Bouton mode sombre : bascule le thème de tout le site et le mémorise.
            const darkBtn = document.getElementById('darkModeToggle');
            const root = document.documentElement;

            function syncDark() {
                darkBtn.classList.toggle('on', root.getAttribute('data-theme') === 'dark');
            }
            syncDark();

            darkBtn.addEventListener('click', function() {
                const isDark = root.getAttribute('data-theme') === 'dark';
                if (isDark) {
                    root.removeAttribute('data-theme');
                    try {
                        localStorage.setItem('theme', 'light');
                    } catch (e) {}
                } else {
                    root.setAttribute('data-theme', 'dark');
                    try {
                        localStorage.setItem('theme', 'dark');
                    } catch (e) {}
                }
                syncDark();
            });
        })();
    </script>
</body>

</html>