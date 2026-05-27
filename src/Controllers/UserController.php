<?php

namespace MVC\Controllers;

use MVC\Models\UserManager;

class UserController
{
    private UserManager $user_manager;

    public function __construct()
    {
        $this->user_manager = new UserManager();
    }

}