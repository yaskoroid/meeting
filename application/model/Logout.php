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
     * @var Service\Auth;
     */
    private $_authService;

    function __construct() {
        parent::__construct();
        $this->_authService = ServiceLocator::authService();
    }

    public function actionIndex() {
        $this->_authService->deauth();
    }
}