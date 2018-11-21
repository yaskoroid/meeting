<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 18.07.2017
 * Time: 21:55
 */

namespace controller;

use core\Controller;

class About extends Controller
{

    function actionIndex()
    {
        // TODO: Implement actionIndex() method.
        $this->view->generate('About', 'Base');
    }
}