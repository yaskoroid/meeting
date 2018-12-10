<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 26.11.2018
 * Time: 11:48
 */

namespace controller;

use core\Controller;
use model;

class Confirm extends Controller\Base {

    public function __construct() {
        $this->model = new model\Confirm();
        parent::__construct();
    }

    function actionIndex() {
        $templateParams = array_merge(
            $this->model->getData(),
            array(
                'header' => 'There is no action to confirmation, sorry'
            )
        );
        $this->view->render('Confirm', $templateParams);
    }

    function actionUsercreationconfirmation() {
        $templateParams = array_merge(
            $this->model->getData(),
            array(
                'header' => 'Подтверждение создания аккаунта',
                'intent' => 'User creation confirmation',
                'cancel' => array (
                    'text' => 'Отменить создание аккаунта',
                    'name' => 'cancelUserCreate',
                ),
                'submit' => array (
                    'text' => 'Подтвердить создание аккаунта',
                    'name' => 'userCreate',
                )
            )
        );

        $this->view->render('Confirm', $templateParams);
    }

    function actionUserdeletionconfirmation() {
        $templateParams = array_merge(
            $this->model->getData(),
            array(
                'header' => 'Подтверждение удаление аккаунта',
                'intent' => 'User deletion confirmation',
                'cancel' => array (
                    'text' => 'Отменить удаление аккаунта',
                    'name' => 'cancelUserCreate',
                ),
                'submit' => array (
                    'text' => 'Подтвердить удаление аккаунта',
                    'name' => 'userCreate',
                )
            )
        );

        $this->view->render('Confirm', $templateParams);
    }

    function actionUsertypechanging() {
        $templateParams = array_merge(
            $this->model->getData(),
            array(
                'header' => 'Изменение типа аккаунта пользователя',
                'intent' => 'User type changing',
                'cancel' => array (
                    'text' => 'Отменить изменение типа аккаунта',
                    'name' => 'cancelChangeType',
                ),
                'submit' => array (
                    'text' => 'Изменить тип аккаунта',
                    'name' => 'changeType',
                )
            )
        );

        $this->view->render('Confirm', $templateParams);
    }

    function actionUserpasswordchanging() {
        $templateParams = array_merge(
            $this->model->getData(),
            array(
                'header' => 'Изменение пароля пользователя',
                'intent' => 'User password changing',
                'inputs' => array(
                    array(
                        'text'        => 'Новый пароль',
                        'name'        => 'newPassword',
                        'placeholder' => 'Новый пароль',
                        'autofocus'   => true,
                    ),
                ),
                'cancel' => array (
                    'text' => 'Отменить изменение пароля',
                    'name' => 'cancelChangePassword',
                ),
                'submit' => array (
                    'text' => 'Изменить пароль',
                    'name' => 'changePassword',
                )
            )
        );

        $this->view->render('Confirm', $templateParams);
    }

    function actionUserchangeemailrequestconfirmation() {
        $templateParams = array_merge(
            $this->model->getData(),
            array(
                'header' => 'Запрос на изменение email пользователя',
                'intent' => 'User change email request confirmation',
                'cancel' => array (
                    'text' => 'Отменить разрешение измененить email',
                    'name' => 'cancelChangeEmailRequest',
                ),
                'submit' => array (
                    'text' => 'Разрешить изменение email',
                    'name' => 'changeEmailRequest',
                )
            )
        );

        $this->view->render('Confirm', $templateParams);
    }

    function actionUserchangeemailconfirmation() {
        $templateParams = array_merge(
            $this->model->getData(),
            array(
                'header' => 'Подтверждение изменения email пользователя',
                'intent' => 'User change email confirmation',
                'cancel' => array (
                    'text' => 'Отменить изменение email',
                    'name' => 'cancelChangeEmail',
                ),
                'submit' => array (
                    'text' => 'Изменить email',
                    'name' => 'changeEmail',
                )
            )
        );

        $this->view->render('Confirm', $templateParams);
    }
}