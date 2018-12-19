<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 12.12.2018
 * Time: 17:47
 */

namespace Service;

class Task extends Basic {

    /**
     * @var array
     */
    public static $entities = array(
        'task',
        'taskPartner'
    );

    function __construct() {
        self::_initServices();
    }

    private function _initServices() {

    }
}