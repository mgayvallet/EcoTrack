<?php

namespace MVC\Controllers;

use MVC\Models\QuestionManager;
use MVC\Models\CarbonCalculator;
use MVC\Models\EmpreinteManager;
use MVC\Models\DefiManager;

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
        $defiManager = new DefiManager();
        $userId = (int) $_SESSION['user_id'];

        $empreinte    = $defiManager->getLatestEmpreinte($userId);
        $posteFocus   = $defiManager->getPosteFocus($userId);
        $defis        = $defiManager->getDailyDefis($userId, 3);
        $stats        = $defiManager->getStats($userId);
        $completedIds = $defiManager->getCompletedTodayIds($userId);

        ob_start();
        require VIEWS . 'App/challenge.php';
        $content = ob_get_clean();

        $title = 'EcoTrack - Défis';
        require VIEWS . 'layout/layout.php';
    }

    public function showContactPage()
    {
        ob_start();
        require VIEWS . 'App/contact.php';
        $content = ob_get_clean();

        $title = 'EcoTrack - Contact';
        require VIEWS . 'layout/layout.php';
    }

    public function validateChallenge($id)
    {
        $defiManager = new DefiManager();
        $defiManager->validateDefi((int) $_SESSION['user_id'], (int) $id);

        header('Location: /challenge');
        exit;
    }

    public function calculate()
    {
        $calculator = new CarbonCalculator();
        $result = $calculator->calculate($_POST);

        if (isset($_SESSION['user_id'])) {
            $empreinteManager = new EmpreinteManager();
            $empreinteManager->save($_SESSION['user_id'], $result);
        }

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
