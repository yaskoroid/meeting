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
     * @var array
     */
    private $_result = array(
        'page'        => 'Confirm',
        'title'       => 'Подтверджение действия',
        'description' => 'Страница выполнения подтверждений изменений в системе через email пользователя',
        'keywords'    => 'Подтверждения, email, система'
    );

    function __construct() {
        parent::__construct();
        self::_initServices();
    }

    protected function _initServices() {
        $this->_changeConfirmService = ServiceLocator::changeConfirmService();
    }

    public function getData() {
        return $this->_result;
    }

    public function usercreation($hash) {
        $response = 'Вы успешно подтвердили создание пользователя, Вам отправлено письмо для изменения пароля';
        $error = false;
        try {
            $this->_changeConfirmService->createAfterConfirmUser($hash);
        } catch (\Throwable $t) {
            $response = $t->getMessage();
            $error = true;
        } catch (\Exception $e) {
            $response = $e->getMessage();
            $error = true;
        }
        return array(
            'responseText' => $response,
            'error' => $error
        );
    }

    public function passwordchange($hash, $newPassword) {
        $response = 'Вы успешно изменили пароль';
        $error = false;
        try {
            $this->_changeConfirmService->changeAfterConfirmUserPassword($hash, $newPassword);
        } catch (\Throwable $t) {
            $response = $t->getMessage();
            $error = true;
        } catch (\Exception $e) {
            $response = $e->getMessage();
            $error = true;
        }
        return array(
            'responseText' => $response,
            'error' => $error
        );
    }
}