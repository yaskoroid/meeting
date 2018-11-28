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
     * @var Service\User\Profile
     */
    private $_userProfileService;

    /**
     * @var Service\User\Type
     */
    private $_userTypeService;

    function __construct() {
        self::_initServices();
    }

    private function _initServices() {
        $this->_contextService     = ServiceLocator::contextService();
        $this->_templateService    = ServiceLocator::templateService();
        $this->_userProfileService = ServiceLocator::userProfileService();
        $this->_userTypeService    = ServiceLocator::userTypeService();
    }

    /**
     * @param string $derivedView - dynamic content of view page
     * @param array $data - data of model
     */
    function generate($derivedView, array $data = array())
    {
        //@TODO test som code
        $this->changeConfirmService = ServiceLocator::changeConfirmService();
        //create user
        //var_dump($this->changeConfirmService->createChangeUserCreation($this->_userProfileService->getRandomUser()));

        // change email
        //var_dump($this->changeConfirmService->createChangeUserEmailRequest($this->_userProfileService->getUserByEmail($this->_userProfileService->getRandomUser()->email), 'iskoroid@gmail.com'));
        //var_dump($this->changeConfirmService->createChangeUserEmailRequest($this->_userProfileService->getUserById(16), 'skoroid12345@gmail.com'));

        // delete user
        //var_dump($this->changeConfirmService->createChangeUserDelete($this->_userProfileService->getUserById(17)));

        //var_dump($this->changeConfirmService->createAfterConfirmUser('2436ff971f7d2c0c3077da88b8deec7c81ae1c804485b7ef41f208402a932b65e9549171c8adc51f9f28e0fa1f25e088a222e19dceb3aee7ea92879e484ce9e7'));
        //var_dump($this->changeConfirmService->changeAfterConfirmUserPassword('fd3404e6253f4a66ae2569d7d421682bd0cb8563141cac6f3c722c5cdcbf73793fc5d30144639003c88a460bea9450b357e3cd031be561fdf81dd1dbe15778c3', 'asdfsdf'));



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