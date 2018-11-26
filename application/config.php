<?php
// Данные для подключения к БД на сайте zzz.com.ua
/*define(DB_HOST,"mysql.zzz.com.ua");
define(DB_USER,"user_skoroid");
define(DB_USER_PASSWORD,"user_skoroid");
define(DB_NAME,"iskoroid");*/
namespace application;

$projectPath = substr(dirname(__FILE__), 0, strrpos(dirname(__FILE__), DIRECTORY_SEPARATOR));

// Administrator
$GLOBALS['config']['admin'] = array(
    'name'    => 'Vasya',
    'surname' => 'Pupkin',
    'email'   => 'skoroid12345@gmail.com',
);
// Database
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

// Email
$GLOBALS['config']['email_service.smtp.host'] = 'smtp.gmail.com';
$GLOBALS['config']['email_service.smtp.port'] = '465';
$GLOBALS['config']['email_service.smtp.user'] = 'skoroid12345@gmail.com';
$GLOBALS['config']['email_service.smtp.password'] = '19A@ne63';
$GLOBALS['config']['email_service.smtp.email_from'] = 'skoroid12345@gmail.com';

$GLOBALS['config']['email']['imap_port'] = '993';
$GLOBALS['config']['email']['imap_host'] = 'imap.gmail.com';
$GLOBALS['config']['email']['skoroid12345@gmail.com'] = '19A@ne63';

// Site
$GLOBALS['site'] = array(
    'http' => 'http',
    'domain' => 'roman.com',
);

// Files
$GLOBALS['config']['file']['log'] = array(
    'path' => 'log',
);

$GLOBALS['config']['paths']['vendor'] = $projectPath . DIRECTORY_SEPARATOR . 'vendor';
$GLOBALS['config']['composer']['autoload'] = $GLOBALS['config']['paths']['vendor'] . DIRECTORY_SEPARATOR . 'autoload.php';
$GLOBALS['config']['autoload']['Twig-2.5.0'] = $GLOBALS['config']['composer']['autoload'];
$GLOBALS['config']['autoload']['PhpMailer-6.0.6'] = $GLOBALS['config']['composer']['autoload'];

$templatePath = DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'template';
$GLOBALS['config']['paths']['templates'] = array(
    'view'  => $projectPath . $templatePath . DIRECTORY_SEPARATOR . 'View',
    'email' => $projectPath . $templatePath . DIRECTORY_SEPARATOR . 'Email',
);
$GLOBALS['config']['paths']['templates']['css'] = array(
    'email' => $GLOBALS['config']['paths']['templates']['email'] . DIRECTORY_SEPARATOR . 'css',
);