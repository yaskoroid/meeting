<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 03.07.2017
 * Time: 16:48
 */

namespace controller;

use core\Controller;
use model\ModelHome;

/*
 * Класс выводит главную страницу
 */
class ControllerHome extends Controller
{

    public function __construct()
    {
        // Создаем модель и вид из родителя
        $this->model = new ModelHome();
        parent::__construct();
    }

    // Основное действие контроллера
    public function actionIndex()
    {
        // Если отправляется форма или AJAX
        if (!empty($_POST))
        {
            // Если нажата кнопка добавить задачу
            if (isset($_POST['addTask'])) {
                // Добавляем задачу
                $this->model->createTask($_POST);
                // Генерируем данные для вида и сам вид
                $this->view->generate("Home", "Template", $this->model->getData());
            } elseif (isset($_POST['ajax'])) {
                // Генерируем ответ на AJAX запрос
                $this->view->generateJson($this->model->handleAjaxJson($_POST));
            } else {
                // Генерируем ответ на другие действия пользователя
                // например сортировка и изменение страницы
                $this->view->generate("Home", "Template", $this->model->getData());
            }
        } else {
            // Генерируем данные для вида и сам вид
            $this->view->generate("Home", "Template", $this->model->getData());
        }
    }
}