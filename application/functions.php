<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 07.11.2018
 * Time: 14:26
 */

require_once "config.php";

function logThrowable(\Throwable $t) {
    $date = new DateTime();
    $dir = __DIR__ . DIRECTORY_SEPARATOR . $GLOBALS['config']['file']['log']['path'];
    createDirIfNotExists($dir);
    $file = fopen($dir . '/logThrowable.txt', 'a+');
    fwrite($file, $date->format('c') . ' '. $t->getMessage() . PHP_EOL);
}

function logException(\Exception $e) {
    $date = new DateTime();
    $dir = __DIR__ . DIRECTORY_SEPARATOR . $GLOBALS['config']['file']['log']['path'];
    createDirIfNotExists($dir);
    $file = fopen($dir . '/logException.txt', 'a+');
    fwrite($file, $date->format('c') . ' '. $e->getMessage() . PHP_EOL);
}

function createDirIfNotExists($path) {
    if (empty($path)) {
        throw new \Exception('No directory path');
    }
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }
}