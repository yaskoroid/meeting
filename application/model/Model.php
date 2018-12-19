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

    private static $_privateMethods = array(
        '_initAjaxServices',
        '_initRenderServices',
        '_initRenderData',
    );

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

        $shortMethodName       = '_' . strtolower(substr($post['intent'], 0, strpos($post['intent'], ' ')));
        $shortMethodNameAction = strtolower(substr($post['intent'], strpos($post['intent'], ' ') + 1));

        $methodName = $this->_utilsService->spacedStringToMethodName($post['intent']);
        $methodName = preg_replace('/_/', '', $methodName);
        $methodName = '_' . $methodName;

        if (!empty($shortMethodName) &&
            !empty($methodName) &&
            !method_exists($this, $methodName) &&
            method_exists($this, $shortMethodName) &&
            !in_array($shortMethodName, self::$_privateMethods))
            return $this->{$shortMethodName}($shortMethodNameAction, $post);

        if (empty($methodName))
            throw new \InvalidArgumentException('Bad intent');

        if (!method_exists($this, $methodName))
            throw new \InvalidArgumentException('No such method for this intent');

        if (in_array($methodName, self::$_privateMethods))
            throw new \InvalidArgumentException('Requested method is private');

        return $this->{$methodName}($post);
    }
}