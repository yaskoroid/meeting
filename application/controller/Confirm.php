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
        $this->view->generate('Confirm', $templateParams);
    }

    function actionUsercreation() {
        $templateParams = array_merge(
            $this->model->getData(),
            array(
                'header' => 'Подтверждение создания пользователя',
                'action' => '',
                'method' => 'post',
                'submit' => array (
                    'submitText' => 'Подтвердить создание пользователя',
                    'submitName' => 'userCreate',
                )
            )
        );

        if (!empty($_POST['usercreate']))
            //var_dump($_POST);
            $templateParams = array_merge(
                $templateParams,
                array(
                    'response' => $this->model->usercreation($_GET['hash'])
                )
            );

        unset($_POST);

        $this->view->generate('Confirm', $templateParams);
    }

    function actionPasswordchange() {
        $templateParams = array_merge(
            $this->model->getData(),
            array(
                'header' => 'Изменение пароля пользователя',
                'action' => '',
                'method' => 'post',
                'inputs' => array(
                    array(
                        'text'        => 'Новый пароль',
                        'name'        => 'newPassword',
                        'placeholder' => 'Новый пароль',
                        'autofocus'   => true,
                    ),
                ),
                'submit' => array (
                    'submitText' => 'Изменить пароль',
                    'submitName' => 'changePassword',
                )
            )
        );

        if (!empty($_POST))
            $templateParams = array_merge(
                $templateParams,
                array(
                    'response' => $this->model->passwordchange($_GET['hash'], $_POST['newPassword'])
                )
            );

        unset($_POST);

        $this->view->generate('Confirm', $templateParams);
    }
}