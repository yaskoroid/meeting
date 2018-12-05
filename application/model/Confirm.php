<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 26.11.2018
 * Time: 14:31
 */

namespace model;

use core\Service\ServiceLocator;
use Service;

class Confirm extends Model
{
    /**
     * @var Service\ChangeConfirm
     */
    private $_changeConfirmService;

    /**
     * @var Service\Validator
     */
    private $_validatorService;

    function __construct() {
        parent::__construct();
    }

    protected function _initAjaxServices() {
        $this->_changeConfirmService = ServiceLocator::changeConfirmService();
        $this->_validatorService     = ServiceLocator::validatorService();
    }

    protected function _initRenderServices() {}

    protected function _initRenderData() {
        $this->_result = array(
            'page'        => 'Confirm',
            'title'       => 'Подтверджение действия',
            'description' => 'Страница выполнения подтверждений изменений в системе через email пользователя',
            'keywords'    => 'Подтверждения, email, система'
        );
    }

    /**
     * @param array $post
     * @return string
     */
    protected function _userCreationConfirmation($post) {
        if ($post['cancel'] === 'true') {
            if (!$this->_changeConfirmService->cancelUserChangeConfirm(Service\ChangeConfirm::CREATE_USER, $post['hash']))
                throw new \LogicException('Could not cancel your account creation');
            return array('text' => 'Вы успешно отменили создание своего аккаунта');
        }

        $this->_validatorService->check(array('hash128' => $post['hash']));

        $this->_changeConfirmService->createAfterConfirmUser($post['hash']);
        return array('text' => 'Вы успешно подтвердили создание аккаунта, Вам отправлено письмо для изменения пароля');
    }

    /**
     * @param array $post
     * @return string
     */
    protected function _userDeletionConfirmation($post) {
        if ($post['cancel'] === 'true') {
            if (!$this->_changeConfirmService->cancelUserChangeConfirm(Service\ChangeConfirm::DELETE_USER, $post['hash']))
                throw new \LogicException('Could not cancel your account deletion');
            return array('text' => 'Вы успешно отменили удаление своего аккаунта');
        }

        $this->_validatorService->check(array('hash128' => $post['hash']));

        $this->_changeConfirmService->changeAfterConfirmUserDelete($post['hash']);
        return array('text' => 'Вы успешно подтвердили удаление аккаунта');
    }

    /**
     * @param array $post
     * @return string
     */
    protected function _userPasswordChanging($post) {
        if ($post['cancel'] === 'true') {
            if (!$this->_changeConfirmService->cancelUserChangeConfirm(Service\ChangeConfirm::CHANGE_USER_PASSWORD, $post['hash']))
                throw new \LogicException('Could not cancel your account password changing');
            return array('text' => 'Вы успешно отменили изменение пароля своего аккаунта');
        }

        $this->_validatorService->check(
            array(
                'password' => $post['newPassword'],
                'hash128' => $post['hash']
            )
        );

        $this->_changeConfirmService->changeAfterConfirmUserPassword($post['hash'], $post['newPassword']);
        return array('text' => 'Вы успешно изменили пароль');
    }

    /**
     * @param array $post
     * @return string
     */
    protected function _userChangeEmailRequestConfirmation($post) {
        if ($post['cancel'] === 'true') {
            if (!$this->_changeConfirmService->cancelUserChangeConfirm(Service\ChangeConfirm::CHANGE_USER_EMAIL_REQUEST, $post['hash']))
                throw new \LogicException('Could not cancel request your account email changing');
            return array('text' => 'Вы успешно отменили разрешение на изменение email своего аккаунта');
        }

        $this->_validatorService->check(array('hash128' => $post['hash']));

        $this->_changeConfirmService->createAfterConfirmUserEmailRequestChangeUserEmail($post['hash']);
        return array(
            'text' => 'Вы только что разрешили изменить email своего аккаунта, теперь подтвердите другой email'
        );
    }

    /**
     * @param array $post
     * @return string
     */
    protected function _userChangeEmailConfirmation($post) {
        if ($post['cancel'] === 'true') {
            if (!$this->_changeConfirmService->cancelUserChangeConfirm(Service\ChangeConfirm::CHANGE_USER_EMAIL, $post['hash']))
                throw new \LogicException('Could not cancel your account email changing');
            return array('text' => 'Вы успешно отменили изменение email своего аккаунта');
        }

        $this->_validatorService->check(array('hash128' => $post['hash']));

        $this->_changeConfirmService->changeAfterConfirmUserEmail($post['hash']);
        return array('text' => 'Вы успешно изменили email своего аккаунта');
    }
}