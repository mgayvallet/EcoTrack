<?php

namespace MVC\Controllers;

use MVC\Models\QuestionManager;
use MVC\Models\CarbonCalculator;

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
        $questionManager = new QuestionManager();
        $categories = $questionManager->getQuestionsByCategory();

        ob_start();
        require VIEWS . 'App/calculator.php';
        $content = ob_get_clean();

        $title = 'EcoTrack - Calculateur';
        require VIEWS . 'layout/layout.php';
    }

    public function showChallengePage()
    {
        ob_start();
        require VIEWS . 'App/challenge.php';
        $content = ob_get_clean();

        $title = 'EcoTrack - Défis';
    }

    public function calculate()
    {
        $calculator = new CarbonCalculator();
        $result = $calculator->calculate($_POST);

        ob_start();
        require VIEWS . 'App/result.php';
        $content = ob_get_clean();

        $title = 'EcoTrack - Votre résultat';
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
