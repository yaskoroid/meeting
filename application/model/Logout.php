<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 05.11.2018
 * Time: 11:35
 */

namespace model;

use core\Service\ServiceLocator;
use Service;

class Logout extends Model {
    /**
     * @var Service\Core\Auth;
     */
    private $_authService;

    function __construct() {
        parent::__construct();
        self::_initServices();
    }

    private function _initServices() {
        $this->_authService = ServiceLocator::authService();
    }

    protected function _initAjaxServices() {}

    protected function _initRenderServices() {}

    protected function _initRenderData() {}

    public function actionIndex() {
        $this->_authService->deauth();
    }
}