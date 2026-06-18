<?php

session_start();

require '../src/config/config.php';
require '../vendor/autoload.php';
require SRC . 'helper.php';

$router = new MVC\Router(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
$router->get('/', "HomeController@index");
$router->get('/calculator', "HomeController@showCalculatorPage")->auth();
$router->get('/challenge', "HomeController@showChallengePage")->auth();
$router->get('/challenge/validate/:id', "HomeController@validateChallenge")->auth();
$router->get('/contact', "HomeController@showContactPage")->auth();
$router->post('/calculator', "HomeController@calculate")->auth();

$router->get('/login', "UserController@loginPage");
$router->post('/login', "UserController@login");

$router->get('/register', "UserController@registerPage");
$router->post('/register', "UserController@register");

$router->get('/forgot-password', "UserController@forgotPasswordPage");
$router->post('/forgot-password', "UserController@forgotPassword");

$router->get('/reset-password', "UserController@resetPasswordPage");
$router->post('/reset-password', "UserController@resetPassword");

$router->get('/logout', "UserController@logout");

$router->run();
