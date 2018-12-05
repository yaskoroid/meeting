<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 27.11.2018
 * Time: 11:49
 */

namespace model;

use core\Service\ServiceLocator;
use Service;

class Settings extends Model {

    function __construct() {
        parent::__construct();
    }

    protected function _initAjaxServices() {}

    protected function _initRenderServices() {}

    protected function _initRenderData() {
        $this->_result = array(
            'page'        => 'Settings',
            'title'       => 'Настройки',
            'description' => 'Настройки в приложении',
            'keywords'    => 'Настройки, приложение-задачник'
        );
    }
}