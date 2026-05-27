<?php

namespace MVC\Controllers;

use MVC\Models\UserManager;

class HomeController
{
    private UserManager $user_manager;

    public function __construct()
    {
        $this->user_manager = new UserManager();
    }

    public function login(): void
    {
        require VIEWS . 'Auth/login.php';
    }

    public function register(): void
    {
        require VIEWS . 'Auth/register.php';
    }
}