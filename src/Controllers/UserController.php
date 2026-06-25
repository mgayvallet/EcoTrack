<?php

namespace MVC\Controllers;

use MVC\Models\UserManager;
use MVC\Models\User;
use MVC\Validator;

// Gestion de l'authentification : inscription, connexion, réinitialisation de mdp
class UserController
{
    private UserManager $user_manager;

    public function __construct()
    {
        $this->user_manager = new UserManager();
    }

    public function loginPage()
    {
        ob_start();
        require VIEWS . 'Auth/login.php';
        $content = ob_get_clean();

        $title = 'Connexion';
        require VIEWS . 'layout/layout-user.php';
    }

    // Valide les identifiants et établit la session
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $validator = new Validator($_POST);
        $validator->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6']
        ]);

        if (!empty($validator->errors())) {
            $_SESSION['error'] = $validator->errors();
            $_SESSION['old'] = $_POST;
            header('Location: /login');
            exit;
        }

        $result = $this->user_manager->login($email, $password);

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
            header('Location: /calculator');
            exit;
        } else {
            $_SESSION['error']['login'] = $result['message'];
            $_SESSION['old'] = $_POST;
            header('Location: /login');
            exit;
        }
    }

    public function registerPage()
    {
        ob_start();
        require VIEWS . 'Auth/register.php';
        $content = ob_get_clean();

        $title = 'Inscription';
        require VIEWS . 'layout/layout-user.php';
    }

    // Crée un nouvel utilisateur et le connecte automatiquement
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            exit;
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        $validator = new Validator($_POST);
        $validator->validate([
            'name' => ['required', 'min:3', 'max:255'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ]);

        if ($password !== $password_confirm) {
            $_SESSION['error']['password_confirm'] = 'Les mots de passe ne correspondent pas';
        }

        if (!empty($validator->errors()) || isset($_SESSION['error']['password_confirm'])) {
            $_SESSION['error'] = $_SESSION['error'] ?? [];
            $_SESSION['old'] = $_POST;
            unset($_SESSION['old']['password']);
            unset($_SESSION['old']['password_confirm']);
            header('Location: /register');
            exit;
        }

        $user = new User($name, $email, $password);
        $result = $this->user_manager->register($user);

        if ($result['success']) {
            $this->user_manager->login($email, $password);
            $_SESSION['success'] = $result['message'];
            header('Location: /calculator');
            exit;
        } else {
            $_SESSION['error']['register'] = $result['message'];
            $_SESSION['old'] = $_POST;
            unset($_SESSION['old']['password']);
            unset($_SESSION['old']['password_confirm']);
            header('Location: /register');
            exit;
        }
    }

    public function forgotPasswordPage()
    {
        $title = 'Mot de passe oublié';
        require VIEWS . 'Auth/forgot-password.php';
    }

    // Envoie un email avec un lien de réinitialisation (token de 1h)
    public function forgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /forgot-password');
            exit;
        }

        $email = $_POST['email'] ?? '';

        $validator = new Validator($_POST);
        $validator->validate([
            'email' => ['required', 'email'],
        ]);

        if (!empty($validator->errors())) {
            $_SESSION['error'] = $_SESSION['error'] ?? [];
            $_SESSION['old'] = $_POST;
            header('Location: /forgot-password');
            exit;
        }

        $result = $this->user_manager->sendPasswordReset($email);

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error']['forgot'] = $result['message'];
        }
        header('Location: /forgot-password');
        exit;
    }

    // Valide le token et affiche le formulaire de changement
    public function resetPasswordPage()
    {
        $title = 'Réinitialiser le mot de passe';
        $token = $_GET['token'] ?? '';

        if (empty($token) || !$this->user_manager->getEmailByToken($token)) {
            $_SESSION['error']['reset'] = 'Ce lien de réinitialisation est invalide ou a expiré.';
            header('Location: /forgot-password');
            exit;
        }

        require VIEWS . 'Auth/reset-password.php';
    }

    // Met à jour le mot de passe et invalide tous les autres tokens
    public function resetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        $validator = new Validator($_POST);
        $validator->validate([
            'password' => ['required', 'min:8'],
        ]);

        if ($password !== $password_confirm) {
            $_SESSION['error']['password_confirm'] = 'Les mots de passe ne correspondent pas';
        }

        if (!empty($validator->errors()) || isset($_SESSION['error']['password_confirm'])) {
            $_SESSION['error'] = $_SESSION['error'] ?? [];
            header('Location: /reset-password?token=' . urlencode($token));
            exit;
        }

        $result = $this->user_manager->resetPassword($token, $password);

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
            header('Location: /login');
            exit;
        } else {
            $_SESSION['error']['reset'] = $result['message'];
            header('Location: /forgot-password');
            exit;
        }
    }

    public function logout()
    {
        UserManager::logout();
        $_SESSION['success'] = 'Vous avez été déconnecté avec succès';
        header('Location: /login');
        exit;
    }
}
