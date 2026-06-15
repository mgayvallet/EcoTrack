<?php

namespace MVC\Controllers;

use MVC\Models\UserManager;

class HomeController
{
    public function index(): void
    {
        ob_start();
        require VIEWS . 'App/homepage.php';
        $content = ob_get_clean();

        $title = 'EcoTrack - Accueil';
        require VIEWS . 'layout/layout.php';
    }

    public function showCalculatorPage()
    {
        if (!UserManager::isLoggedIn()) {
            header('Location: /login');
            exit;
        }

        ob_start();
        require VIEWS . 'App/calculator.php';
        $content = ob_get_clean();

        $title = 'EcoTrack - Calculateur';
        require VIEWS . 'layout/layout.php';
    }

    public function login()
    {
        $title = 'EcoTrack - Login';
        require VIEWS . 'Auth/login.php';
    }

    public function register()
    {
        $title = 'EcoTrack - Register';
        require VIEWS . 'Auth/register.php';
    }
}
