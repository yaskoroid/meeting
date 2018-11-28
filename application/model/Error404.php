<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 27.11.2018
 * Time: 12:12
 */

namespace model;

class Error404 extends Model {

    function __construct() {
        parent::__construct();
        self::_initResult();
    }

    private function _initResult() {
        $this->_result = array(
            'page'        => 'error404',
            'title'       => 'Ошибка 404 - страница не найдена',
            'description' => 'Страница на найдена',
            'keywords'    => 'Страница на найдена, ошибка'
        );
    }
}