<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 30.10.2018
 * Time: 10:14
 */

namespace controller;

use core\Controller;
use model;

/*
 * Класс выводит страницу пользователя
 */
class User extends Controller
{

    public function __construct()
    {
        // Создаем модель и вид из родителя
        $this->model = new model\User();
        parent::__construct();
    }

    // Основное действие контроллера
    public function actionIndex()
    {
        // Генерируем данные для вида и сам вид
        $this->view->generate('User', 'Base', $this->model->getData());
    }

    // Основное действие контроллера
    public function actionJson()
    {
        // Генерируем ответ на AJAX запрос
        try {
            $result = array(
                'error'    => null,
                'response' => $this->model->handleAjaxJson($_POST)
            );
        } catch(\Throwable $t) {
            $result = array(
                'error'    => true,
                'response' => $t->getMessage()
            );
        } catch (\Exception $e) {
            $result = array(
                'error'    => true,
                'response' => $e->getMessage()
            );
        }
        $this->view->generateJson($result);
    }
}