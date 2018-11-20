<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 04.07.2017
 * Time: 13:17
 */

namespace controller;


use core\Controller;

/*
 * Класс вы водит страницу 404
 */
class Controller404 extends Controller
{

    // Основное действие контроллера
    public function actionIndex()
    {
        // Отправляем заголовок 404
        header('HTTP/1.1 404 Not Found');
        // Создаем вид страницы 404
        $this->view->generate("404", "Template", array("title" => "Страница 404"));
    }
}