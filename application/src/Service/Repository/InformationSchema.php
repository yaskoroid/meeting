<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 12.11.2018
 * Time: 13:50
 */

namespace Service\Repository;

use Entity;
use Respect;

class InformationSchema extends Repository
{

    function __construct() {
        parent::__construct();
        $this->_init();
    }

    private function _init() {
        $this->_connection = $this->_databaseService->informationSchemaConnection();
        $this->_mapper = Repository::mapper($this->_connection);
        $this->_mapper->entityNamespace = "\\Entity\\InformationSchema\\";
        $this->_mapper->setStyle(new Respect\Data\Styles\InformationSchema());
        $this->_db = new Respect\Relational\Db($this->_connection);
    }

    /**
     * @return array
     */
    public function getMeetingUserColumns() {
        /** @var Entity\InformationSchema\Columns[] */
        $columns = $this->_loadObjects(
            array(
                'TABLE_SCHEMA' => $GLOBALS['config']['database']['meeting']['name'],
                'TABLE_NAME'   => 'user',
            ),
            'Columns'
        );
        if ($columns === null) {
            return null;
        }

        return $columns;
        $userTableColumnsList = $this->_loadObjectsColumns('user', $columns);
    }
}