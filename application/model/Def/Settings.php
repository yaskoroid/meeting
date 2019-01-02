<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 13.12.2018
 * Time: 13:23
 */

namespace model\Def;

use core\Service\ServiceLocator;
use Service;

class Settings extends Def {

    public static $constSettings            = array();
    public static $constSettingsNames       = array();
    public static $constSettingsFieldsNames = array();

    /**
     * @var Service\Entity\Base
     */
    private $_entityBaseService;

    function __construct() {
        $this->_entityBaseService = ServiceLocator::entityBaseService();

        self::$constSettings = $this->_entityBaseService->getEntitiesSettingsNames();
        array_walk(self::$constSettings, function($item, $key) {
            self::$constSettingsNames[$item]       = $this->_entityBaseService->getEntityName($item);
            self::$constSettingsFieldsNames[$item] = $this->_entityBaseService->getEntityFieldsNames($item);
        });
    }

    public function get() {
        return parent::_getRun();
    }
}