<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 27.11.2018
 * Time: 11:45
 */

namespace controller;

use core\Controller;
use model;

class Settings extends Controller\Base {

    function __construct() {
        $this->model = new model\Settings();
        parent::__construct();
    }

    public function actionIndex() {
        $this->view->generate('Settings', $this->model->getData());
    }
}
