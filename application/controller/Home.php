<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 03.07.2017
 * Time: 16:48
 */

namespace controller;

use core\Controller;
use model;

class Home extends Controller\Base {

    public function __construct() {
        $this->model = new model\Home();
        parent::__construct();
    }

    public function actionIndex() {

        if (!empty($_POST)) {
            if (isset($_POST['addTask'])) {

                $this->model->createTask($_POST);
                $this->view->generate('Home', $this->model->getData());
            } elseif (isset($_POST['ajax'])) {
                // Генерируем ответ на AJAX запрос
                $this->view->generateJson($this->model->handleAjaxJson($_POST));
            } else {
                $this->view->generate('Home', $this->model->getData());
            }
            return;
        }

        unset($_POST);

        $this->view->generate('Home', $this->model->getData());
    }
}