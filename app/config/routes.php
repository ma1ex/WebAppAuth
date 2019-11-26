<?php

/**
 * Project: auth.local;
 * File: routes.php;
 * Developer: Matvienko Alexey (matvienko.alexey@gmail.com);
 * Date & Time: 24.11.2019, 22:31
 * Comment: Routes config
 */

/**
 * @example ['controller' => 'controllerName',
 *          'action       => 'actionName',
 *          'namespace'   => 'App\Name\Space',
 *          'name'        => 'this_route_name for build menu',
 *          'visible'      => 'visible link']
 */
return [
    '' => [ // URL
        // Контроллер, который будет срабатывать на URL
        'controller' => 'main',
        // Метод контроллера
        'action' => 'index',
        // Пространство имен, откуда загружать контроллер
        'namespace' => 'app\Controllers',
        // Имя страницы для вывода в меню
        'name' => 'Main page'
    ],

    'about' => [
        'controller' => 'main',
        'action' => 'about',
        'namespace' => 'app\Controllers',
        'name' => 'About'
    ],

    'auth/register' => [
        'controller' => 'auth',
        'action' => 'register',
        'namespace' => 'app\Controllers\Auth',
        'name' => 'Register'
    ],

    'auth/login' => [
        'controller' => 'auth',
        'action' => 'login',
        'namespace' => 'app\Controllers\Auth',
        'name' => 'Login',
        'visible' => 'unautorize'
    ],

    'auth/add' => [
        'controller' => 'auth',
        'action' => 'add',
        'namespace' => 'app\Controllers\Auth'
    ],

    'auth/signin' => [
        'controller' => 'auth',
        'action' => 'signin',
        'namespace' => 'app\Controllers\Auth'
    ],

    'auth/logout' => [
        'controller' => 'auth',
        'action' => 'logout',
        'namespace' => 'app\Controllers\Auth',
        'name' => 'Logout',
        'visible' => 'autorize'
    ],
];