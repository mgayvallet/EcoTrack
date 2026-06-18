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
        <h1>Mot de passe oublié</h1>
        <p class="subtitle">Entrez votre email pour recevoir un lien de réinitialisation</p>

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

            <form method="POST" action="/forgot-password">
                <div class="field">
                    <label for="email">Adresse email</label>
                    <div class="input-wrap">
                        <img class="icon" src="assets/icons/email.svg" alt="">
                        <input type="email" id="email" name="email" placeholder="exemple@gmail.com" value="<?= htmlspecialchars(old('email')) ?>" required>
                    </div>
                </div>

                <button type="submit">Envoyer le lien de réinitialisation</button>
            </form>
        </div>

        <div class="footer">
            <p><a href="/login">Retour à la connexion</a></p>
        </div>
    </div>
</body>

</html>
