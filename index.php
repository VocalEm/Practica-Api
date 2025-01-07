<?php

require_once 'router.php';


$router = new Router();

$router->add('/usuarios', 'GET', 'HomeController@index');

$router->run();
