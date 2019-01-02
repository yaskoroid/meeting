<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 11.12.2018
 * Time: 15:20
 */

namespace Service\Entity;

use Service;

class Settings extends Base {

    function __construct() {
        parent::__construct();
        self::_initServices();
    }

    private function _initServices() {

    }
}