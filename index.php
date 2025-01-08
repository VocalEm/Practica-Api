<?php

require_once 'router.php';
require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$router = new Router();

$router->add('/usuarios', 'GET', 'HomeController@index');
$router->add('/usuarios/{id}', 'GET', 'HomeController@show');
$router->add('/crear_usuario', 'POST', 'HomeController@store');

$router->run();
