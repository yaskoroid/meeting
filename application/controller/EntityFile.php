<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 27.12.2018
 * Time: 16:17
 */

namespace controller;

use core\Controller;
use core\Route;
use model;

class EntityFile extends Controller\Base {

    function __construct() {
        $this->model = new model\File();
        parent::__construct();
    }

    public function actionIndex() {
        Route::errorPage404();
    }
}