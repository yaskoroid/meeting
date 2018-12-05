<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 26.11.2018
 * Time: 17:22
 */

namespace model;

use core\Model\Base;
use core\Service\ServiceLocator;
use Service;

abstract class Model extends Base {

    /**
     * @var array
     */
    protected $_result = array();

    /**
     * @var array
     */
    protected $_frontendConstants = array();

    /**
     * @var Service\Utils
     */
    protected $_utilsService;

    function __construct() {
        self::_initServices();
    }

    abstract protected function _initAjaxServices();
    abstract protected function _initRenderServices();
    abstract protected function _initRenderData();

    private function _initServices() {
        $this->_utilsService = ServiceLocator::utilsService();
    }

    /**
     * @return array
     */
    public function getData() {
        $this->_initRenderServices();

        $this->_initRenderData();

        $this->_result['frontendConstants'] = $this->_frontendConstants;
        return $this->_result;
    }

    /**
     * @param array $post
     * @return array
     * @throws \Throwable
     * @throws \Exception
     */
    public function handleAjaxJson(array $post) {
        $this->_initAjaxServices();

        $methodName = $this->_utilsService->spacedStringToMethodName($post['intent']);
        $methodName = preg_replace('/_/', '', $methodName);
        $methodName = '_' . $methodName;
        if (empty($methodName))
            throw new \InvalidArgumentException('Bad intent');

        if (!method_exists($this, $methodName))
            throw new \InvalidArgumentException('No such method for this intent');

        return $this->{$methodName}($post);
    }
}