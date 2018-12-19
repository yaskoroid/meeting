<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 30.10.2018
 * Time: 10:14
 */

namespace controller;

use core\Controller;
use model;

class User extends Controller\Base {

    public function __construct() {
        $this->model = new model\User();
        parent::__construct();
    }

    public function actionIndex() {
        $this->view->render('User', $this->model->getData());
    }
}