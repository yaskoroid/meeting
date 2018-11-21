<?php
// Данные для подключения к БД на сайте zzz.com.ua
/*define(DB_HOST,"mysql.zzz.com.ua");
define(DB_USER,"user_skoroid");
define(DB_USER_PASSWORD,"user_skoroid");
define(DB_NAME,"iskoroid");*/
namespace application;

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
$GLOBALS['config']['paths']['vendor'] = '../vendor';
$GLOBALS['config']['autoload']['Twig-2.5.0'] = '/twig/autoload.php';
$GLOBALS['config']['paths']['templates'] = '/view';