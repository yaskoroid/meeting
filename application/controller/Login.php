<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 03.07.2017
 * Time: 16:47
 */

namespace controller;

use core\Controller;
use model;

/*
 * Класс выводит страницу логина
 */
class Login extends Controller
{

    function __construct()
    {
        // Создаем модель и вид из родителя
        $this->model = new model\Login();
        parent::__construct();
    }

    // Основное действие контроллера
    public function actionIndex()
    {
        // Если отправляется форма
        if (!empty($_POST))
        {
            // Вызываем метод авторизации из модели
            $data = $this->model->login($_POST['login2'],$_POST['password']);

            // Проверяем получилось ли авторизироваться
            if (empty($data['error']))
            {
                $this->model->setCustomizableSessionValues();
            }
            // Выводим вид с результатом авторизации
            $this->view->generate('Login', 'Base', $data);
        }
        else
        {
            // Выводим вид
            $this->view->generate('Login', 'Base', $this->model->getData());
        }

    }
}