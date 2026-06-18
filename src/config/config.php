<?php
define("SRC", '../src/');
define("CONTROLLERS", '../src/Controllers/');
define("MODELS", '../src/Models/');
define("VIEWS", '../src/Views/');

define('HOST', '127.0.0.1');
define('DATABASE', 'EcoTrack');
define('USER', 'root');
define('PASSWORD', '');

// Configuration SMTP (envoi des emails via PHPMailer)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'ecotack1@gmail.com');      // <-- ton adresse Gmail
define('SMTP_PASSWORD', 'xekpkiwhjjxwufxj');                     // <-- mot de passe d'application Gmail (16 caractères)
define('SMTP_FROM', 'ecotack1@gmail.com');
define('SMTP_FROM_NAME', 'EcoTrack');