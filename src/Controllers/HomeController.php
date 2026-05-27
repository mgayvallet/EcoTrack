<?php

namespace MVC\Controllers;

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
}