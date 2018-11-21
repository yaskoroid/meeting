<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 05.11.2018
 * Time: 11:35
 */

namespace model;

use core\ServiceLocator;
use Service;


class Logout
{
    /**
     * @var Service\Auth;
     */
    private $_authService;

    function __construct() {
        $this->_authService = ServiceLocator::authService();
        $this->_authService->deauth();
    }
}