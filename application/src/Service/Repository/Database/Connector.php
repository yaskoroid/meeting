<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 03.07.2017
 * Time: 19:21
 */

namespace Service\Repository\Database;

/*
 * Класс обслуживает операции с базой данных создавая объект MySQLi
 */
class Connector extends  Basic
{

    /**
     * @var \mysql
     */
    private $_mysqli;

    function __construct()
    {

    }

    public function connect()
    {
        // Подключаемся к БД
        $this->_mysqli = new \mysqli(
            $config['database']['meeting']['host'],
            $config['database']['meeting']['user'],
            $config['database']['meeting']['password'],
            $config['database']['meeting']['name']
        ) or die(mysql_error());

        // Ставим кодировку utf-8
        $this->_mysqli->set_charset('utf8');
    }

    public function close()
    {
        // Получаем id процесса обработки объекта MySQLi
        $thread = $this->_mysqli->thread_id;

        // Закрываем поток
        $this->_mysqli->kill($thread);

        // Удаляем объект MySQLi
        $this->_mysqli->close();
    }

    public function singleRequest($query, $fieldsToCheck) {
        $this->connect();
        $resultRequest = $this->request($query, $fieldsToCheck);
        $this->close();
        return $resultRequest;
    }

    /**
     * @param array $query
     * @param array $fieldsToCheck
     * @return mixed
     * @throws \Exception
     */
    public function request(array $query, array $fieldsToCheck) {

        if (!is_array($fieldsToCheck)) {
            throw new \InvalidArgumentException('Fields must be an array');
        }

        if (!is_array($query)) {
            throw new \InvalidArgumentException('Query must be an array');
        }

        $fieldsToCheckCount = count($fieldsToCheck);
        $queryCount         = count($query);
        $rightProportionOfElementsCount = $fieldsToCheckCount === $queryCount - 1 || $fieldsToCheckCount === $queryCount;

        if (!$rightProportionOfElementsCount) {
            throw new \InvalidArgumentException('The number of fields must be less than one or equal to the number of queries');
        }

        if (!$this->_mysqli) {
            throw new \InvalidArgumentException('Connection to SQL must be opened');
        }

        foreach($fieldsToCheck as $key=>$field) {
            $fieldsToCheck[$key] = $this->_checkInjection($field);
        }

        $resultQuery = '';
        foreach($query as $key=>$field) {
            $resultQuery .= $field;
            if ($key < $fieldsToCheckCount) $resultQuery .= $fieldsToCheck[$key];
        }

        // Запрос в БД
        return $this->_query($resultQuery);
    }

    /**
     * @param $str
     * @return mixed
     */
    private function _checkInjection($str)
    {
        return $this->_mysqli->real_escape_string($str);
    }

    /**
     * @param $query
     * @return mixed
     * @throws \Exception
     */
    private function _query($query)
    {
        if (empty($query))
            throw new \Exception('Query string is empty');

        try {
            // Подключаемся к БД
            /** @var mixed $requestResult */
            $requestResult = $this->_mysqli->query($query);

            // Проверяем результат
            $this->_checkResult($requestResult);

            return $requestResult;
        } catch (mysqli_sql_exception $e) {
            throw new \Exception('MySQLi status is: ' . $this->_mysqli->sqlstate);
        }
    }

    /**
     * @return null
     */
    private function _getLastAutoinctement()
    {
        // Подключаемся к БД
        /** @var mixed $requestResult */
        $requestResult = $this->query("SELECT LAST_INSERT_ID()");

        // Извлекаем ряд значений
        /** @var array */
        $myrow = $requestResult->fetch_assoc();
        return $myrow ? $myrow['LAST_INSERT_ID()'] : null;
    }

    /**
     * @param mixed $requestResult
     * @throws \Exception
     */
    private function _checkResult($requestResult)
    {
        // Проверяем не нулевой ли объект mysqli_result
        if ($requestResult === null)
            throw new \Exception('Object is null, maybe bad request to database!');
    }
}