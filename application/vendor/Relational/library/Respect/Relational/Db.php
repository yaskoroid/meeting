<?php
// @codingStandardsIgnoreStart
namespace Respect\Relational;

use \PDO as PDO;

/**
 * Class Db
 *
 * @method Db update($tableName)
 * @method Db set(array $fields)
 * @method Db select($fields)
 * @method Db from($tableName)
 * @method Db where(array $conditions)
 * @method Db orderBy($order)
 * @method Db desc()
 * @method Db asc()
 * @method Db limit($limit)
 * @method Db groupBy($group)
 * @method Db groupByBinary($group)
 * @method Db having($having)
 *
 * @package Respect\Relational
 */
class Db
{

    protected $connection;
    protected $currentSql;
    protected $protoSql;

    public function __call($methodName, $arguments)
    {
        $this->currentSql->__call($methodName, $arguments);
        return $this;
    }

    public function __construct(PDO $connection, Sql $sqlPrototype = null)
    {
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection = $connection;
        $this->protoSql = $sqlPrototype ? : new Sql();
        $this->_setCurrentSql();
    }

    public function exec()
    {
        return (bool) $this->executeStatement();
    }

    public function fetch($object = '\stdClass', $extra = null)
    {
        $result = $this->performFetch(__FUNCTION__, $object, $extra);
        return is_callable($object) ? $object($result) : $result;
    }

    public function fetchAll($object = '\stdClass', $extra = null)
    {
        $result = $this->performFetch(__FUNCTION__, $object, $extra);
        return is_callable($object) ? array_map($object, $result) : $result;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function getSql()
    {
        return $this->currentSql;
    }

    public function prepare($queryString, $object = '\stdClass', array $extra = null)
    {
        $statement = $this->connection->prepare($queryString);

        if (is_int($object))
            $statement->setFetchMode($object);
        elseif ('\stdClass' === $object || 'stdClass' === $object)
            $statement->setFetchMode(PDO::FETCH_OBJ);
        elseif (is_callable($object))
            $statement->setFetchMode(PDO::FETCH_OBJ);
        elseif (is_object($object))
            $statement->setFetchMode(PDO::FETCH_INTO, $object);
        elseif (is_array($object))
            $statement->setFetchMode(PDO::FETCH_ASSOC);
        elseif (is_null($extra))
            $statement->setFetchMode(PDO::FETCH_CLASS, $object);
        else
            $statement->setFetchMode(PDO::FETCH_CLASS, $object, $extra);

        return $statement;
    }

    public function query($rawSql)
    {
        $this->currentSql->setQuery($rawSql);
        return $this;
    }

    public function executeStatement($object = '\stdClass', $extra = null)
    {
        $statement = $this->prepare((string) $this->currentSql, $object, $extra);

        //TODO In php >= 5.5 can use try-finally
        try {
            $statement->execute($this->currentSql->getParams());
        } catch (\Throwable $t) {
            $this->_setCurrentSql();
            throw $t;
        } catch (\Exception $e) {
            $this->_setCurrentSql();
            throw $e;
        }
        $this->_setCurrentSql();
        return $statement;
    }

    protected function performFetch($method, $object = '\stdClass', $extra = null)
    {
        $statement = $this->executeStatement($object, $extra);
        $result = $statement->{$method}();
        return $result;
    }

    private function _setCurrentSql() {
        $this->currentSql = clone $this->protoSql;
    }
}