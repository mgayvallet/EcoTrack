<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/auth.css">
    <title><?= htmlspecialchars($title ?? 'EcoTrack') ?></title>
</head>

<body>
    <div class="container">
        <h1>Rejoignez EcoTrack</h1>
        <p class="subtitle">Commencez votre parcours vers un mode de vie plus durable</p>

        <div class="card">

            <div class="field">
                <label for="name">Nom complet</label>
                <div class="input-wrap">
                    <img class="icon" src="assets/icons/name.svg" alt="">
                    <input type="text" id="name" placeholder="LeBron James">
                </div>
            </div>

            <div class="field">
                <label for="email">Adresse email</label>
                <div class="input-wrap">
                    <img class="icon" src="assets/icons/email.svg" alt="">
                    <input type="email" id="email" placeholder="exemple@gmail.com">
                </div>
            </div>

            <div class="field">
                <div class="label-row">
                    <label for="password">Mot de passe</label>
                    <a href="#" class="forgot">Mot de passe oublié ?</a>
                </div>
                <div class="input-wrap">
                    <img class="icon" src="assets/icons/password.svg" alt="">
                    <input type="password" id="password" placeholder="••••••••">
                </div>
            </div>

            <div class="remember">
                <input type="checkbox" id="remember">
                <span>Se souvenir de moi</span>
            </div>

            <button type="button">Se connecter</button>
        </div>

        <div class="footer">
            <p>Vous avez déjà un compte ? <a href="/login">Se connecter</a></p>
            <p><a href="/">Retour à l'accueil</a></p>
        </div>
    </div>
</body>

</html>