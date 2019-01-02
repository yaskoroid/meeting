<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 27.11.2018
 * Time: 12:12
 */

namespace model;

use core\Service\ServiceLocator;
use Service;

class Error404 extends Model {

    /**
     * Service\Path
     */
    private $_pathService;

    function __construct() {
        parent::__construct();
    }

    protected function _initAjaxServices() {}

    protected function _initRenderServices() {
        $this->_pathService = ServiceLocator::pathService();
    }

    protected function _initRenderData() {
        $this->_result = array(
            'page'             => 'error404',
            'title'            => 'Ошибка 404 - страница не найдена',
            'description'      => 'Страница на найдена',
            'keywords'         => 'Страница на найдена, ошибка',
            'image404FilePath' => $this->_pathService->adapterFromHttpAccess($this->_pathService->getFileTypePath('etc')) .
                '/404.jpg'
        );
    }
}