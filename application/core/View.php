<?php
namespace core;

use core\Service\ServiceLocator;
use model\Def;
use Service;

class View {

    /**
     * @var Service\Context
     */
    private $_contextService;

    /**
     * @var Service\Template
     */
    private $_templateService;

    /**
     * @var Service\User\Profile
     */
    private $_userProfileService;

    /**
     * @var Service\User\Type
     */
    private $_userTypeService;

    function __construct() {
        $this->_initServices();
    }

    private function _initServices() {
        $this->_contextService     = ServiceLocator::contextService();
        $this->_templateService    = ServiceLocator::templateService();
        $this->_userProfileService = ServiceLocator::userProfileService();
        $this->_userTypeService    = ServiceLocator::userTypeService();
        $this->_emailService    = ServiceLocator::emailService();
        print $this->_emailService->create('1', '2', Service\Email::USER_CREATE_CONFIRM);
    }

    /**
     * @param string $derivedView - dynamic content of view page
     * @param array $data - data of model
     */
    function generate($derivedView, array $data = array())
    {
        $data['def']  = $this->_getDef($derivedView);

        $user = $this->_contextService->getUser();
        $this->_userProfileService->filterSecureUserFields($user);
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
    function generateJson(array $data = array()) {
        print json_encode($data, JSON_HEX_QUOT, 20);
    }

    /**
     * Function returns Defaults object
     * @param string $view
     * @return array
     */
    private function _getDef($view) {

        $defClassName = 'model\\Def\\' . $view;
        $defFileName = \Autoloader::getClassPath($defClassName);

        if (file_exists($defFileName)) {
            $def = new $defClassName;
            return $def->get();
        }
        return array();
    }
}