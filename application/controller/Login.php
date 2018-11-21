<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 03.07.2017
 * Time: 16:47
 */

namespace controller;

use core\Controller;
use model;

class Login extends Controller {

    function __construct() {
        $this->model = new model\Login();
        parent::__construct();
    }

    public function actionIndex() {
        if (!empty($_POST)) {
            $this->view->generate('Login', $this->model->login($_POST['login'], $_POST['password']));
            return;
        }

        $this->view->generate('Login', $this->model->getData());
    }
}