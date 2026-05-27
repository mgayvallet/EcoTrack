<?php

session_start();

require '../src/config/config.php';
require '../vendor/autoload.php';
require SRC . 'helper.php';

$router = new MVC\Router($_SERVER["REQUEST_URI"]);
$router->get('/', "HomeController@index");
$router->get('/calculator', "HomeController@showCalculatorPage");

$router->run();
