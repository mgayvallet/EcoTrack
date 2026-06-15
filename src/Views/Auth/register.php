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
    <h1>Rejoignez EcoTrack</h1>
    <p class="subtitle">Commencez votre parcours vers un mode de vie plus durable</p>

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

      <form method="POST" action="/register">
        <div class="field">
          <label for="name">Nom complet</label>
          <div class="input-wrap">
            <img class="icon" src="assets/icons/name.svg" alt="">
            <input type="text" id="name" name="name" placeholder="LeBron James" value="<?= htmlspecialchars(old('name')) ?>" required>
          </div>
        </div>
      
        <div class="field">
          <label for="email">Adresse email</label>
          <div class="input-wrap">
            <img class="icon" src="assets/icons/email.svg" alt="">
            <input type="email" id="email" name="email" placeholder="exemple@gmail.com" value="<?= htmlspecialchars(old('email')) ?>" required>
          </div>
        </div>

        <div class="field">
          <label for="password">Mot de passe (minimum 8 caractères)</label>
          <div class="input-wrap has-toggle">
            <img class="icon" src="assets/icons/password.svg" alt="">
            <input type="password" id="password" name="password" placeholder="••••••••" required minlength="8">
            <button type="button" class="toggle-password" aria-label="Afficher le mot de passe">
              <svg class="eye" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                <circle cx="12" cy="12" r="3" />
              </svg>
              <svg class="eye-off" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                <line x1="1" y1="1" x2="23" y2="23" />
              </svg>
            </button>
          </div>
        </div>

        <div class="field">
          <label for="password_confirm">Confirmer le mot de passe</label>
          <div class="input-wrap has-toggle">
            <img class="icon" src="assets/icons/password.svg" alt="">
            <input type="password" id="password_confirm" name="password_confirm" placeholder="••••••••" required minlength="8">
            <button type="button" class="toggle-password" aria-label="Afficher le mot de passe">
              <svg class="eye" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                <circle cx="12" cy="12" r="3" />
              </svg>
              <svg class="eye-off" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                <line x1="1" y1="1" x2="23" y2="23" />
              </svg>
            </button>
          </div>
        </div>

        <div class="remember">
          <input type="checkbox" id="terms" name="terms" required>
          <span>J'accepte les <a href="#" class="terms-link">conditions d'utilisation</a></span>
        </div>

        <button type="submit">S'inscrire</button>
      </form>
    </div>

    <div class="footer">
      <p>Vous avez déjà un compte ? <a href="/login">Se connecter</a></p>
      <p><a href="/">Retour à l'accueil</a></p>
    </div>
  </div>

  <script>
    document.querySelectorAll(".toggle-password").forEach((btn) => {
      btn.addEventListener("click", () => {
        const input = btn.parentElement.querySelector("input");
        const show = input.type === "password";
        input.type = show ? "text" : "password";
        btn.classList.toggle("is-visible", show);
        btn.setAttribute("aria-label", show ? "Masquer le mot de passe" : "Afficher le mot de passe");
      });
    });
  </script>
</body>
</html>
