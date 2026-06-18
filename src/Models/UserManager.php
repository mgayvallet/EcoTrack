<?php

namespace MVC\Models;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class UserManager
{
    private $bdd;

    public function __construct()
    {
        try {
            $this->bdd = new \PDO(
                'mysql:host=' . HOST . ';dbname=' . DATABASE . ';charset=utf8mb4',
                USER,
                PASSWORD,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (\PDOException $e) {
            die('Erreur de connexion à la base de données : ' . $e->getMessage());
        }
    }

    public function register(User $user)
    {
        try {
            if ($this->getUserByEmail($user->getEmail())) {
                return ['success' => false, 'message' => 'Cet email est déjà utilisé'];
            }

            $user->hashPassword();

            $query = $this->bdd->prepare('
                INSERT INTO users (name, email, password, created_at)
                VALUES (:name, :email, :password, NOW())
            ');

            $query->execute([
                ':name' => $user->getName(),
                ':email' => $user->getEmail(),
                ':password' => $user->getPassword(),
            ]);

            return ['success' => true, 'message' => 'Inscription réussie ! Vous pouvez maintenant vous connecter.'];
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => 'Erreur lors de l\'inscription : ' . $e->getMessage()];
        }
    }

    public function login($email, $password)
    {
        try {
            $user = $this->getUserByEmail($email);

            if (!$user) {
                return ['success' => false, 'message' => 'Email ou mot de passe incorrect'];
            }

            if (!$user->verifyPassword($password)) {
                return ['success' => false, 'message' => 'Email ou mot de passe incorrect'];
            }

            $_SESSION['user_id'] = $user->getId();
            $_SESSION['user_name'] = $user->getName();
            $_SESSION['user_email'] = $user->getEmail();

            return ['success' => true, 'message' => 'Connexion réussie !'];
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => 'Erreur lors de la connexion : ' . $e->getMessage()];
        }
    }

    public function getUserByEmail($email)
    {
        try {
            $query = $this->bdd->prepare('SELECT * FROM users WHERE email = :email');
            $query->execute([':email' => $email]);

            $data = $query->fetch();

            if (!$data) {
                return null;
            }

            $user = new User();
            $user->setId($data['id']);
            $user->setName($data['name']);
            $user->setEmail($data['email']);
            $user->setPassword($data['password']);
            $user->setCreatedAt($data['created_at']);
            $user->setUpdatedAt($data['updated_at']);

            return $user;
        } catch (\PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function getUserById($id)
    {
        try {
            $query = $this->bdd->prepare('SELECT * FROM users WHERE id = :id');
            $query->execute([':id' => $id]);

            $data = $query->fetch();

            if (!$data) {
                return null;
            }

            $user = new User();
            $user->setId($data['id']);
            $user->setName($data['name']);
            $user->setEmail($data['email']);
            $user->setPassword($data['password']);
            $user->setCreatedAt($data['created_at']);
            $user->setUpdatedAt($data['updated_at']);

            return $user;
        } catch (\PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function sendPasswordReset($email)
    {
        try {
            $user = $this->getUserByEmail($email);

            // On ne révèle pas si l'email existe ou non (anti-énumération)
            if (!$user) {
                return ['success' => true, 'message' => 'Si un compte existe avec cet email, un lien de réinitialisation vient d\'être envoyé.'];
            }

            $token = bin2hex(random_bytes(32));

            $query = $this->bdd->prepare('
                INSERT INTO password_resets (email, token, created_at)
                VALUES (:email, :token, NOW())
            ');
            $query->execute([
                ':email' => $email,
                ':token' => $token,
            ]);

            $this->sendResetEmail($email, $token);

            return ['success' => true, 'message' => 'Si un compte existe avec cet email, un lien de réinitialisation vient d\'être envoyé.'];
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => 'Erreur lors de l\'envoi : ' . $e->getMessage()];
        }
    }

    private function sendResetEmail($email, $token)
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $resetLink = $scheme . '://' . $host . '/reset-password?token=' . $token;

        $subject = 'Réinitialisation de votre mot de passe EcoTrack';
        $body = $this->renderTemplate('Emails/reset-password.php', [
            'resetLink' => $resetLink,
        ]);

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USER;
            $mail->Password   = SMTP_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = SMTP_PORT;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = "Réinitialisez votre mot de passe : " . $resetLink;

            $mail->send();
        } catch (PHPMailerException $e) {
            // L'erreur d'envoi ne doit pas révéler d'info à l'utilisateur,
            // mais on la journalise pour le débogage.
            error_log('Erreur envoi email reset : ' . $mail->ErrorInfo);
        }
    }

    public function getEmailByToken($token)
    {
        try {
            $query = $this->bdd->prepare('
                SELECT email FROM password_resets
                WHERE token = :token
                AND created_at >= (NOW() - INTERVAL 1 HOUR)
                ORDER BY id DESC LIMIT 1
            ');
            $query->execute([':token' => $token]);
            $data = $query->fetch();

            return $data ? $data['email'] : null;
        } catch (\PDOException $e) {
            return null;
        }
    }

    public function resetPassword($token, $password)
    {
        try {
            $email = $this->getEmailByToken($token);

            if (!$email) {
                return ['success' => false, 'message' => 'Ce lien de réinitialisation est invalide ou a expiré.'];
            }

            $user = new User('', $email, $password);
            $user->hashPassword();

            $query = $this->bdd->prepare('
                UPDATE users SET password = :password WHERE email = :email
            ');
            $query->execute([
                ':password' => $user->getPassword(),
                ':email' => $email,
            ]);

            // On invalide tous les tokens de cet email
            $del = $this->bdd->prepare('DELETE FROM password_resets WHERE email = :email');
            $del->execute([':email' => $email]);

            return ['success' => true, 'message' => 'Votre mot de passe a été réinitialisé avec succès. Vous pouvez vous connecter.'];
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => 'Erreur lors de la réinitialisation : ' . $e->getMessage()];
        }
    }

    private function renderTemplate($template, array $data = [])
    {
        extract($data);
        ob_start();
        require VIEWS . $template;
        return ob_get_clean();
    }

    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public static function logout()
    {
        session_destroy();
        return ['success' => true, 'message' => 'Déconnecté avec succès'];
    }
}
