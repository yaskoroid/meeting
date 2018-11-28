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

class Model extends Base {

    /**
     * @var array
     */
    protected $_result = array();

    /**
     * @var Service\Utils
     */
    protected $_utilsService;

    function __construct() {
        self::_initServices();
    }

    private function _initServices() {
        $this->_utilsService = ServiceLocator::utilsService();
    }

    /**
     * @return array
     */
    public function getData(){
        return $this->_result;
    }

    /**
     * @param array $post
     * @return array
     * @throws \Throwable
     * @throws \Exception
     */
    public function handleAjaxJson(array $post) {
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