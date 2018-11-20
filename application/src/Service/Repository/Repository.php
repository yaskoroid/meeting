<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 08.11.2018
 * Time: 10:15
 */

namespace Service\Repository;

use application\Autoloader;
use core\Service\ServiceLocator;
use Service\Utils;
use Service\Repository\Database;
use Respect\Relational;
use Service\Basic;

abstract class Repository extends Basic
{

    /**
     * @var \SplObjectStorage
     */
    private static $_mappers;

    /**
     * @var Utils
     */
    protected $_utilsService;

    /**
     * @var \PDO
     */
    protected $_connection;

    /**
     * @var Relational\Db
     */
    protected $_db;

    /**
     * @var Relational\Mapper
     */
    protected $_mapper;

    /**
     * @var Database
     */
    protected $_databaseService;

    private $_mapperNamespace = 'Entity';

    function __construct() {
        $this->_initServices();
    }

    /**
     * @param \PDO $connection
     * @return Relational\Mapper
     */
    protected static function mapper(\PDO $connection) {
        if (is_null(self::$_mappers)) {
            self::$_mappers = new \SplObjectStorage();
        }
        if (isset(self::$_mappers[$connection])) {
            $mapper = self::$_mappers[$connection];
        } else {
            $mapper = new Relational\Mapper($connection);
            self::$_mappers[$connection] = $mapper;
        }
        return $mapper;
    }

    /**
     * @param string $mapperName
     * @param string $table
     * @return array
     */
    protected function _getAllObjects($mapperName, $table) {
        $this->_monitoringStartRepository('{$table}');
        $requestResult = $this->_connectorService->singleRequest(
            array(
                'SELECT * FROM ',
            ),
            array($table)
        );
        $mapperClassName = $this->_getMapper($mapperName);
        $res = array();
        while ($row = mysqli_fetch_assoc($requestResult)) {
            array_push($res, $this->_utilsService->fillObjectBy(new $mapperClassName, $row));
        }
        $this->_monitoringStopRepository('{$table}');
        return $res;
    }

    /**
     * @param array $filter
     * @param string $objectName
     * @return array
     */
    protected function _loadObjects(array $filter = [], $objectName) {
        $this->_monitoringStartRepository("_loadObjects_{$objectName}");
        $object = $this->_mapper->$objectName($filter)->fetchAll();
        $this->_monitoringStopRepository("_loadObjects_{$objectName}");
        if (!$this->_databaseService->isValidResult($object)) {
            return null;
        }
        return $object;
    }

    /**
     * @param string $tableName
     * @param string $limit
     * @param string $orderBy
     * @param bool $desc
     * @param array $filter
     * @param string $className
     * @return array
     * @throws DataBase\Query
     */
    protected function _loadObjects123($tableName, $limit = null, $orderBy = null, $desc = true, array $filter = [], $className = null) {
        $this->_monitoringStartRepository("_loadObjects_{$tableName}");
        if ($className === null) {
            $className = $this->_mapper->entityNamespace . $this->_utilsService->underlineToCamelCase($tableName, false);
        }

        $tempQuery = $this->_db->select('*')->from($tableName);
        if (count($filter) > 0) {
            $tempQuery->where($filter);
        }
        if (!is_null($limit)) {
            if (!is_null($orderBy)) {
                $tempQuery->orderBy($orderBy);
                if ($desc) {
                    $tempQuery->desc();
                } else {
                    $tempQuery->asc();
                }
            }
            $tempQuery->limit($limit);
        }

        $result = $this->_dbFetchAll($tempQuery, $className);

        if (!$this->_databaseService->isValidResult($result)) {
            $result = [];
        } else {
            foreach ($result as $object) {
                $this->_mapper->markTracked($object, $this->_mapper->$tableName);
            }
        }
        $this->_monitoringStopRepository("_loadObjects_{$tableName}");
        return $result;
    }

    /**
     * @param Relational\Db $db
     * @param string|array $className
     * @return mixed[]
     * @throws \Exception
     */
    protected function _dbFetchAll(Relational\Db $db, $className) {
        $query = clone $db->getSql();

        try {
            $result = $db->fetchAll($className);
        } catch (\Throwable $t) {
            $exception = new \Exception("Error in query: {$query}, {$t->getMessage()}");
            logThrowable($exception);
            throw $exception;
        } catch (\Exception $e) {
            $exception = new \Exception("Error in query: {$query},  {$e->getMessage()}");
            logException($exception);
            throw $exception;
        }
        return $result;
    }

    /**
     * @param array $params
     * @param string $objectName
     * @return mixed
     */
    protected function _loadObjectByFilter(array $params, $objectName) {
        $this->_monitoringStartRepository("_loadObjectByFilter_{$objectName}");
        $object = $this->_mapper->$objectName($params)->fetch();
        $this->_monitoringStopRepository("_loadObjectByFilter_{$objectName}");
        if (!$this->_databaseService->isValidResult($object)) {
            return null;
        }
        return $object;
    }

    /**
     * @param string $tableName
     * @param string[] $columns
     * @param array $filter
     * @return array
     * @throws DataBase\Query
     */
    protected function _loadObjectsColumns($tableName, array $columns, array $filter = []) {
        $this->_monitoringStartRepository("_loadObjectsColumns_{$tableName}");
        $tempQuery = $this->_db->select(implode(",", $columns))->from($tableName)->where($filter);
        $className = $this->_mapper->entityNamespace . $this->_utilsService->underlineToCamelCase($tableName, false);

        $result = $this->_dbFetchAll($tempQuery, $className);

        $this->_monitoringStopRepository("_loadObjectsColumns_{$tableName}");
        if (!$this->_databaseService->isValidResult($result)) {
            return [];
        }
        return $result;
    }

    /**
     * @param int $objectId
     * @param string $objectName
     * @return mixed
     */
    protected function _loadObjectById($objectId, $objectName) {
        if (!$objectId) {
            return null;
        }
        $this->_monitoringStartRepository("_loadObjectById_{$objectName}");
        $objectMapper = $this->_mapper->$objectName;
        $object = $objectMapper[$objectId]->fetch();
        $this->_monitoringStopRepository("_loadObjectById_{$objectName}");
        if (!$this->_dbService->isValidResult($object)) {
            return null;
        }
        return $object;
    }

    /**
     * @param string $action
     */
    private function _monitoringStartRepository($action) {
        $this->monitoringStart($action, 'Repository');
    }

    /**
     * @param string $action
     */
    private function _monitoringStopRepository($action) {
        $this->monitoringStop($action, 'Repository');
    }

    /**
     * @param string $mapperName
     * @param array $items
     * @return mixed
     */
    protected function _fillMapper($mapperName, $items) {
        $mapperClassName = $this->_getMapper($mapperName);
        return $this->_utilsService->fillObjectBy(new $mapperClassName, $items);
    }

    /**
     * @param mixed $object
     * @return string
     * @throws \Exception
     */
    protected function _getObjectType($object) {
        if ($object instanceof Entity\User) {
            $type = "user";
        } elseif ($object instanceof Entity\UserType) {
            $type = "user_detail";
        } else {
            throw new \Exception("Invalid object type " . get_class($object));
        }
        return $type;
    }

    /**
     * @param string $mapperName
     * @return string
     */
    private function _getMapper($mapperName) {
        $mapperClassName = $this->_getMapperClassName($this->_getMapperNamespaceName($mapperName));
        if (!$this->_isMapperClassExists($mapperClassName)) {
            throw new \InvalidArgumentException('Mapper is not exists');
        }

        return $mapperClassName;
    }

    /**
     * @param string $mapperClassName
     * @return bool
     */
    private function _isMapperClassExists($mapperClassName) {
        return file_exists($mapperClassName) ? true : false;
    }

    /**
     * @param string $mapperNamespaceName
     * @return string
     */
    private function _getMapperClassName($mapperNamespaceName) {
        return Autoloader::getClassPath($mapperNamespaceName);
    }

    /**
     * @param string $mapperName
     * @return string
     */
    private function _getMapperNamespaceName($mapperName) {
        return $this->_mapperNamespace . '\\' .$mapperName;
    }


    private function _initServices() {
        $this->_utilsService     = ServiceLocator::utilsService();
        $this->_databaseService  = ServiceLocator::repositoryDataBaseService();
    }
}