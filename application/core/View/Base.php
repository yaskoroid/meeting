<?php
namespace core\View;

use core\Service\ServiceLocator;
use model\Def;
use Service;

use Entity;

class Base {

    /**
     * @var Service\Context
     */
    private $_contextService;

    /**
     * @var Service\Template
     */
    private $_templateService;

    /**
     * @var Service\Entity\User
     */
    private $_userService;

    /**
     * @var Service\User\Type
     */
    private $_userTypeService;

    function __construct() {
        self::_initServices();
    }

    private function _initServices() {
        $this->_contextService  = ServiceLocator::contextService();
        $this->_templateService = ServiceLocator::templateService();
        $this->_userService     = ServiceLocator::userService();
        $this->_userTypeService = ServiceLocator::userTypeService();
    }

    /**
     * @param string $derivedView - dynamic content of view page
     * @param array $data - data of model
     */
    function render($derivedView, array $data = array()) {
        //@TODO test same code
        $this->changeConfirmService = ServiceLocator::changeConfirmService();
        //create user
        //var_dump($this->changeConfirmService->createChangeUserCreation($this->_userProfileService->getRandomUser()));

        $data['def']  = $this->_getDef($derivedView);

        $user = $this->_contextService->getUser();
        $this->_userService->filterPublicEntityFields('User', $user);
        $data['user'] = $user;

        $data['userType'] = $this->_userTypeService->getUserType($user);

        $isModelData = !empty($data);
        if ($isModelData)
            if (is_array($data))
                array_push($data, $def);

        $derivedTemplate  = strtolower($derivedView) . '.tpl';
        print $this->_templateService->render($derivedTemplate, $data);
    }

    /**
     * Function shows content of array like a JSON
     * @param array $data
     */
    function renderJson(array $data = array()) {
        print json_encode($data, JSON_HEX_QUOT, 20);
    }

    /**
     * Function returns Defaults object
     * @param string $view
     * @return array
     */
    private function _getDef($view) {

        $defViewClassName = 'model\\Def\\' . $view;
        $defViewFileName  = \Autoloader::getClassPath($defViewClassName);

        if (file_exists($defViewFileName)) {
            $defView = new $defViewClassName;
            return $defView->get();
        }

        $defClassName = 'model\\Def\\Def';
        $defFileName = \Autoloader::getClassPath($defClassName);
        if (file_exists($defFileName)) {
            $def = new $defClassName;
            return $def->get();
        }

        return array();
    }
}