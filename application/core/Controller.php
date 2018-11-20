<?php
namespace core;

/*
 * Базовый класс контроллера, содержит вид, модель
 * и метод основного действия
 */
abstract class Controller
{
    /**
     * @var Model
     */
    public $model;

    /**
     * @var View
     */
    public $view;

    function __construct()
    {
        // Создадим объект вида
        $this->view = new View();
    }

    // Метод основного экшна (обязательный)
    abstract function actionIndex();
}