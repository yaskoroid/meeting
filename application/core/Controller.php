<?php
namespace core;

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
        $this->view = new View();
    }

    abstract function actionIndex();
}