<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/auth.css">
    <title><?= htmlspecialchars($title ?? 'EcoTrack') ?></title>
    <style>
        .error-message {
            color: #d32f2f;
            font-size: 0.9em;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        .success-message {
            color: #388e3c;
            background-color: #f1f8e9;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .alert {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Bienvenue sur EcoTrack</h1>
        <p class="subtitle">Connectez-vous pour continuer votre parcours écologique</p>

        <div class="card">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="success-message"><?= htmlspecialchars($_SESSION['success']) ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <?php foreach ($_SESSION['error'] as $field => $message): ?>
                    <div class="alert"><?= htmlspecialchars($message) ?></div>
                <?php endforeach; ?>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form method="POST" action="/login">
                <div class="field">
                    <label for="email">Adresse email</label>
                    <div class="input-wrap">
                        <img class="icon" src="assets/icons/email.svg" alt="">
                        <input type="email" id="email" name="email" placeholder="exemple@gmail.com" value="<?= htmlspecialchars(old('email')) ?>" required>
                    </div>
                </div>

                <div class="field">
                    <div class="label-row">
                        <label for="password">Mot de passe</label>
                        <a href="#" class="forgot">Mot de passe oublié ?</a>
                    </div>
                    <div class="input-wrap">
                        <img class="icon" src="assets/icons/password.svg" alt="">
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="remember">
                    <input type="checkbox" id="remember" name="remember">
                    <span>Se souvenir de moi</span>
                </div>

                <button type="submit">Se connecter</button>
            </form>
        </div>

        <div class="footer">
            <p>Pas encore de compte ? <a href="/register">Crée un compte</a></p>
            <p><a href="/">Retour à l'accueil</a></p>
        </div>
    </div>
</body>

</html>
