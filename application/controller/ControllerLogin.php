<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 03.07.2017
 * Time: 16:47
 */

namespace controller;

use core\Controller;
use model\ModelLogin;

/*
 * Класс выводит страницу логина
 */
class ControllerLogin extends Controller
{

    function __construct()
    {
        // Создаем модель и вид из родителя
        $this->model = new ModelLogin();
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
                $_SESSION['login'] = $_POST['login2'];
                $_SESSION['level'] = $data['level'];
                $_SESSION['unregistered'] = 0;
                setcookie("login", $_POST['login2'], null, "/");
                setcookie("level", $data['level'], null, "/");
                setcookie("unregistered", 0, null, "/");

                $this->model->setCustomizableSessionValues();
            }
            // Выводим вид с результатом авторизации
            $this->view->generate("Login", "Template", $data);
        }
        else
        {
            // Выводим вид
            $this->view->generate("Login", "Template", $this->model->getData());
        }

    }
}