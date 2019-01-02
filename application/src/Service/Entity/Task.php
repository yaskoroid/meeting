<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 12.12.2018
 * Time: 17:47
 */

namespace Service\Entity;

use Service;
use Entity as Ent;

class Task extends Base {

    /**
     * @var array
     */
    public static $_entities = array(
        'task'
    );

    function __construct() {
        parent::__construct();
        self::_initServices();
    }

    private function _initServices() {

    }
}