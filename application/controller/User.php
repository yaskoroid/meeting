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

class User extends Controller {

    public function __construct() {
        $this->model = new model\User();
        parent::__construct();
    }

    public function actionIndex() {
        $this->view->generate('User', 'Base', $this->model->getData());
    }

    public function actionJson() {
        try {
            $result = array(
                'error'    => null,
                'response' => $this->model->handleAjaxJson($_POST)
            );
        } catch(\Throwable $t) {
            $result = array(
                'error'    => true,
                'response' => $t->getMessage()
            );
        } catch (\Exception $e) {
            $result = array(
                'error'    => true,
                'response' => $e->getMessage()
            );
        }
        $this->view->generateJson($result);
    }
}