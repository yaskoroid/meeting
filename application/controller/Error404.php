<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 04.07.2017
 * Time: 13:17
 */

namespace controller;

use core\Controller;

class Error404 extends Controller {

    public function actionIndex() {
        header('HTTP/1.1 404 Not Found');
        $this->view->generate('Error404', array('title' => 'Страница 404'));
    }
}