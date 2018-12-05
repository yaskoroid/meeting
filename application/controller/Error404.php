<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 04.07.2017
 * Time: 13:17
 */

namespace controller;

use core\Controller;
use model;

class Error404 extends Controller\Base {

    public function __construct() {
        $this->model = new model\Error404();
        parent::__construct();
    }

    public function actionIndex() {
        header('HTTP/1.1 404 Not Found');
        $this->view->render('Error404', $this->model->getData());
    }
}