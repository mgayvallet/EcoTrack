<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($title ?? 'EcoTrack') ?></title>
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

            <nav class="site-nav">
                <a href="/calculator">Calculateur</a>
                <a href="/challenge">Défis</a>
                <a href="/articles">Articles</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="user-name"><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></span>
                    <a href="/logout" class="btn-connexion btn-logout">Déconnexion</a>
                <?php else: ?>
                    <a href="/login" class="btn-connexion">Connexion</a>
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
</body>

</html>