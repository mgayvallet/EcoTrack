<?php

session_start();

require '../src/config/config.php';
require '../vendor/autoload.php';
require SRC . 'helper.php';

$router = new MVC\Router($_SERVER["REQUEST_URI"]);
$router->get('/', "HomeController@index");
$router->get('/calculator', "HomeController@showCalculatorPage")->auth();
$router->get('/challenge', "HomeController@showChallengePage")->auth();
$router->get('/challenge/validate/:id', "HomeController@validateChallenge")->auth();
$router->post('/calculator', "HomeController@calculate")->auth();

$router->get('/login', "UserController@loginPage");
$router->post('/login', "UserController@login");

$router->get('/register', "UserController@registerPage");
$router->post('/register', "UserController@register");

$router->get('/logout', "UserController@logout");

$router->run();
