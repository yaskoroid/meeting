<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 04.07.2017
 * Time: 19:32
 */

namespace controller;


use core\Controller;
use model\ModelLogout;
/*
 * Класс делает логаут и редирект на главную
 */
class ControllerLogout extends Controller
{
    function __construct()
    {
        // Создаем модель и вид из родителя
        $this->model = new ModelLogout();
        parent::__construct();
    }

    // Основное действие контроллера
    public function actionIndex()
    {
        // На главную
        header('Location:/');
    }
}