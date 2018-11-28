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

class Task extends Model {

    /**
     * @var Service\Auth
     */
    private $_authService;

    function __construct() {
        parent::__construct();
        self::_initServices();
        self::_initResult();
    }

    private function _initServices() {
        $this->_authService = ServiceLocator::authService();
    }

    private function _initResult() {
        $this->_result = array(
            'page'        => 'Task',
            'title'       => 'Задачи',
            'description' => 'Работа с задачами в приложении',
            'keywords'    => 'Задачи для пользователей'
        );
    }
}