<?php

/**
 * Project: auth.local;
 * File: index.php;
 * Developer: Matvienko Alexey (matvienko.alexey@gmail.com);
 * Date & Time: 22.11.2019, 18:22
 * Comment: Fron Controller
 */

use ma1ex\Core\Router;

// Develop
require_once '../app/libs/dev.php';
// Env config
require_once '../app/config/env.php';
// DB Config
require_once '../app/config/database.php';
// Class autoloader
require_once dirname(__DIR__) . '\vendor\autoload.php';
// Routes config
$routes = require_once '../app/config/routes.php';

session_start();

$router = new Router($routes);
$router->run();

