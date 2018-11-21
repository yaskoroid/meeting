<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 04.07.2017
 * Time: 19:32
 */

namespace controller;

use core\Controller;
use model;

class Logout extends Controller {
    function __construct() {
        $this->model = new model\Logout();
        parent::__construct();
    }

    public function actionIndex() {
        header('Location:/');
    }
}