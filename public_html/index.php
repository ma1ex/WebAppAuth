<?php

/**
 * Project: auth.local;
 * File: index.php;
 * Developer: Matvienko Alexey (matvienko.alexey@gmail.com);
 * Date & Time: 22.11.2019, 18:22
 * Comment:
 */

use ma1ex\Core\Router;

// Develop
require_once '../app/libs/dev.php';
// Env config
require_once '../app/config/env.php';
// Class autoloader
require_once dirname(__DIR__) . '\vendor\autoload.php';
// Routes config
$routes = require_once '../app/config/routes.php';

session_start();

$router = new Router($routes);
$router->run();











//$XMLfile = '../app/database/appdb.xml';
//Db::dropDatabase($XMLfile);
//$db = Db::connect($XMLfile);
//$db->addTable('users');
//$db->addTable('config');
//$db->removeTable('users');
//$pass = password_hash('root1', PASSWORD_DEFAULT);
//$pass = md5('root');
//debug_p($pass);
/*$db->from('users')->insert([
    'name' => 'Alex1',
    'email' => 'alex@auth.local',
    'login' => 'admin1',
    'password' => 'hfghgfhgf'
]);*/
//$db->from('users')->insert([
//    'name' => 'Alex1',
//    'email' => 'alex@auth.local'
//]);
//$db->from('config')->insert([
//    'name' => 'dbname',
//    'email' => 'local_db'
//]);
//$db->from('users')->lastId();
//debug_v($db->from('users')->lastId());
//$db->from('users')->where('name', 'Alex1')->delete();
//$db->from('users')->where('email', 'alex@auth.local')->delete();
//debug_p($db->from('users')->select()->all());
//$db->from('users')->where('name', 'Alex')->update(['email' => 'www@www']);
//echo '<br><br>' . $db;

