<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 08.11.2018
 * Time: 11:07
 */

namespace Service;

use core\Monitoring;

class Basic {

    /**
     * @param string $action
     * @param string $category
     */
    public function monitoringStart($action, $category = 'service') {
        //Monitoring::start($category, $action);
    }

    /**
     * @param string $action
     * @param string $category
     */
    public function monitoringStop($action, $category = 'service') {
        //Monitoring::stop($category, $action);
    }

    /**
     * @return bool
     */
    public static function isSingleton() {
        return false;
    }

    /**
     * @return self
     */
    public static function instanceSingleton() {
        static $instance;
        if (is_null($instance)) {
            $className = get_called_class();
            $instance = new $className();
        }
        return $instance;
    }
}