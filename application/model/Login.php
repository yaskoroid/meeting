<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 04.07.2017
 * Time: 13:32
 */

namespace model;

use core\Service\ServiceLocator;
use Service;
use Service\User;

class Login extends Model
{
    /**
     * @var Service\Auth
     */
    private $_authService;

    /**
     * @var Context
     */
    private $_contextService;

    /**
     * @var User\Profile
     */
    private $_userProfileService;

    /**
     * @var Service\Validator
     */
    private $_validatorService;

    /**
     * @var Service\ChangeConfirm
     */
    private $_changeConfirmService;

    function __construct() {
        parent::__construct();
    }

    protected function _initAjaxServices() {
        $this->_authService          = ServiceLocator::authService();
        $this->_contextService       = ServiceLocator::contextService();
        $this->_userProfileService   = ServiceLocator::userProfileService();
        $this->_validatorService     = ServiceLocator::validatorService();
        $this->_changeConfirmService = ServiceLocator::changeConfirmService();
    }

    protected function _initRenderServices() {}

    protected function _initRenderData() {
        $this->_result = array(
            'page'        => 'Login',
            'title'       => 'Авторизация',
            'description' => 'Авторизация в приложении',
            'keywords'    => 'Авторизация, Web приложение'
        );
    }

    /**
     * @param array $post
     * @return array
     */
    protected function _getLogin(array $post) {
        $this->_validatorService->check(array('login' => $post['login']));

        $result = $this->_authService->auth($post['login'], $post['password']);
        return array_merge(
            $result,
            array(
                'text' => 'You successfully logged in',
            )
        );
    }

    /**
     * @param array $post
     * @return array
     * @throws \InvalidArgumentException
     */
    protected function _createNewPasswordRequest(array $post) {
        $this->_validatorService->check(array('login' => $post['login']));

        $user = $this->_userProfileService->getUserByLogin($post['login']);
        if ($user === null)
            throw new \InvalidArgumentException('Bad login');

        $this->_contextService->executeInUserContext(function() use ($user){
            $this->_changeConfirmService->createChangeUserPassword($user);
        }, $user);

        return array('text' => 'Письмо успешно отправлено на email');
    }
}