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

    function __construct() {
        parent::__construct();
    }

    protected function _initAjaxServices() {}

    protected function _initRenderServices() {}

    protected function _initRenderData() {
        $this->_result = array(
            'page'        => 'Task',
            'title'       => 'Задачи',
            'description' => 'Работа с задачами в приложении',
            'keywords'    => 'Задачи для пользователей'
        );
    }
}