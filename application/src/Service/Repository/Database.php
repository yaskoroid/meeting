<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 08.11.2018
 * Time: 14:52
 */

namespace Service\Repository;

use Service\Basic;

class Database extends Basic
{

    /**
     * @var \PDO
     */
    private $_pdo;

    /**
     * @var \PDOStatement
     */
    private $_pdoStatement;

    function __construct()
    {

    }

    /**
     * @param mixed $result
     * @return bool
     */
    public function isValidResult($result) {
        return ((is_array($result) && count($result) > 0) || is_object($result));
    }

    public function meetingConnection()
    {
        // Подключаемся к БД
        try {
            $db = new \PDO(
                'mysql:host=' . $GLOBALS['config']['database']['meeting']['host'] .
                ';port=3306;dbname=' . $GLOBALS['config']['database']['meeting']['name'] .
                ';charset=utf8',
                $GLOBALS['config']['database']['meeting']['user'],
                $GLOBALS['config']['database']['meeting']['password'],
                []);
        } catch (\PDOException $e) {
            logException($e);
        }
        return $db;
    }

    public function informationSchemaConnection()
    {
        // Подключаемся к БД
        try {
            $db = new \PDO(
                'mysql:host=' . $GLOBALS['config']['database']['information_schema']['host'] .
                ';port=3306;dbname=' . $GLOBALS['config']['database']['information_schema']['name'] .
                ';charset=utf8',
                $GLOBALS['config']['database']['information_schema']['user'],
                $GLOBALS['config']['database']['information_schema']['password'],
                []);
        } catch (\PDOException $e) {
            logException($e);
        }
        return $db;
    }
}