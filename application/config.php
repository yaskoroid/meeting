<?php
// Данные для подключения к БД на сайте zzz.com.ua
/*define(DB_HOST,"mysql.zzz.com.ua");
define(DB_USER,"user_skoroid");
define(DB_USER_PASSWORD,"user_skoroid");
define(DB_NAME,"iskoroid");*/
namespace application;

// Administrator
$GLOBALS['config']['admin'] = array(
    'name'    => 'Vasya',
    'surname' => 'Pupkin',
    'email'   => 'skoroid12345@gmail.com',
);

// Database
$GLOBALS['config']['database']['meeting'] = array(
    'host'     => 'localhost',
    'name'     => 'meeting',
    'user'     => 'skoroid_123',
    'password' => '123',
);
$GLOBALS['config']['database']['information_schema'] = array(
    'host'     => 'localhost',
    'name'     => 'INFORMATION_SCHEMA',
    'user'     => 'skoroid_123',
    'password' => '123',
);

// Site
$GLOBALS['site'] = array(
    'http'   => 'http',
    'domain' => 'roman.com',
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

// Files
$projectPath       = substr(dirname(__FILE__), 0, strrpos(dirname(__FILE__), DIRECTORY_SEPARATOR));
$projectFolderName = substr($projectPath, strrpos($projectPath, DIRECTORY_SEPARATOR) + 1);

$GLOBALS['config']['path']['projectPath']       = $projectPath;
$GLOBALS['config']['path']['projectFolderName'] = $projectFolderName;
$GLOBALS['config']['path']['log'] = $projectPath . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'log';

$GLOBALS['config']['path']['vendor']                      = $projectPath . DIRECTORY_SEPARATOR . 'vendor';
$GLOBALS['config']['path']['composer']['autoload']        = $GLOBALS['config']['path']['vendor'] .
    DIRECTORY_SEPARATOR . 'autoload.php';
$GLOBALS['config']['path']['autoload']['Twig-2.5.0']      = $GLOBALS['config']['path']['composer']['autoload'];
$GLOBALS['config']['path']['autoload']['PhpMailer-6.0.6'] = $GLOBALS['config']['path']['composer']['autoload'];

$relativeTemplatePath = 'application' . DIRECTORY_SEPARATOR . 'template';
$GLOBALS['config']['path']['templates'] = array(
    'view'  => $projectPath . DIRECTORY_SEPARATOR . $relativeTemplatePath . DIRECTORY_SEPARATOR . 'View',
    'email' => $projectPath . DIRECTORY_SEPARATOR . $relativeTemplatePath . DIRECTORY_SEPARATOR . 'Email',
);
$GLOBALS['config']['path']['templates']['css'] = array(
    'email' => $GLOBALS['config']['path']['templates']['email'] . DIRECTORY_SEPARATOR . 'css',
);
$cachePath = $projectPath . DIRECTORY_SEPARATOR . 'cache';
$GLOBALS['config']['path']['cache'] = array(
    'twig' => $cachePath . DIRECTORY_SEPARATOR . 'twig_compilation_cache',
);
$filePath = $projectPath . DIRECTORY_SEPARATOR . 'file';
$GLOBALS['config']['path']['file'] = $filePath;