<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 06.11.2018
 * Time: 11:18
 */

namespace core\Service;

use core\Service;

class BaseServiceLocator {
    /**
     * @var Service\ServiceFactory
     */
    protected static $_factory;

    /**
     * @return Service\ServiceFactory
     */
    protected static function _factory() {
        if (is_null(self::$_factory)) {
            self::$_factory = new Service\ServiceFactory();
        }
        return self::$_factory;
    }
}