<?php
// Данные для подключения к БД на сайте zzz.com.ua
/*define(DB_HOST,"mysql.zzz.com.ua");
define(DB_USER,"user_skoroid");
define(DB_USER_PASSWORD,"user_skoroid");
define(DB_NAME,"iskoroid");*/
namespace application;

$projectPath = substr(dirname(__FILE__), 0, strrpos(dirname(__FILE__), DIRECTORY_SEPARATOR));

// Данные для подключения к БД на локалке
$GLOBALS['config']['database']['meeting'] = array(
    'host' => 'localhost',
    'name' => 'meeting',
    'user' => 'skoroid_123',
    'password' => '123',
);
$GLOBALS['config']['database']['information_schema'] = array(
    'host' => 'localhost',
    'name' => 'INFORMATION_SCHEMA',
    'user' => 'skoroid_123',
    'password' => '123',
);
$GLOBALS['config']['file']['log'] = array(
    'path' => 'log',
);
$GLOBALS['site'] = array(
    'http' => 'http',
    'domain' => 'roman.com',
);

$GLOBALS['config']['paths']['vendor'] = $projectPath . '/vendor';
$GLOBALS['config']['composer']['autoload'] = $GLOBALS['config']['paths']['vendor'] . '/autoload.php';
$GLOBALS['config']['autoload']['Twig-2.5.0'] = $GLOBALS['config']['composer']['autoload'];
$GLOBALS['config']['paths']['templates'] = array(
    'view'  => $projectPath . '/application/template/View',
    'email' => $projectPath . '/application/template/Email',
);
$GLOBALS['config']['paths']['templates']['css'] = array(
    'email' => $projectPath . '/application/template/Email/css',
);