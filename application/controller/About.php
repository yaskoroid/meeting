<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 18.07.2017
 * Time: 21:55
 */

namespace controller;


use core\Controller;

/*
 * Класс выводит о приложении
 */
class About extends Controller
{

    function actionIndex()
    {
        // TODO: Implement actionIndex() method.
        // Генерируем вид задачи для тестового задания
        $this->view->generate('About', 'Base');
    }
}