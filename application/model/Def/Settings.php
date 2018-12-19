<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 13.12.2018
 * Time: 13:23
 */

namespace model\Def;

use Service;

class Settings extends Def {

    public static $constSettings            = array();
    public static $constSettingsNames       = array();
    public static $constSettingsFieldsNames = array();

    function __construct() {
        self::$constSettings            = Service\Settings::$entities;
        self::$constSettingsNames       = Service\Settings::$entitiesNames;
        self::$constSettingsFieldsNames = Service\Settings::$entitiesFieldsNames;
    }

    public function get() {
        return parent::_getRun();
    }
}