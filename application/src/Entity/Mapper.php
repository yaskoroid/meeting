<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 05.11.2018
 * Time: 14:23
 */

namespace Entity;

use application\core\Service\ServiceLocator;
use Service;

class Mapper {

    /**
     * @var Service\Utils;
     */
    //private $_utilsService;

    function __construct() {
        //self::_initServices();
    }

    /**
     * @param array $exceptionFields
     * @return string
     * @throws \Exception
     */
    /*function getFieldsInRow(array $exceptionFields = array()) {
        $properties = $this->getAllProperties($exceptionFields);
        return implode(',', $this->makePropertiesWithUnderline($properties));
    }*/

    /**
     * @param array $exceptionFields
     * @return array
     * @throws \Exception
     */
    /*function getAllProperties(array $exceptionFields = array()) {
        if (!is_array($exceptionFields)) {
            throw new \Exception('ExceptionFields is not array');
        }
        $allProperties = array();
        foreach($this as $key=>$property) {
            if (!in_array($key, $exceptionFields))
                array_push($allProperties, $key);
        }
        return $allProperties;
    }*/

    /**
     * @param array $properties
     * @return array
     * @throws \Exception
     */
    /*function makePropertiesWithUnderline(array $properties) {
        if (!is_array($properties)) {
            throw new \Exception('Properties is not array');
        }
        $result = array();
        foreach($properties as $key => $value) {
            array_push($result, $this->_utilsService->camelCaseToUnderline($value, false));
        }
        return $result;
    }*/

    /*private function _initServices() {
        $this->_utilsService = ServiceLocator::utilsService();
    }*/
}