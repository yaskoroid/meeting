<?php
namespace core\Controller;

use core\View;
use core\Model;

abstract class Base {

    /**
     * @var Model\Base
     */
    public $model;

    /**
     * @var View\Base
     */
    public $view;

    function __construct() {
        $this->view = new View\Base();
    }

    abstract function actionIndex();

    public function actionJson() {
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

        unset($_POST);

        $this->view->generateJson($result);
    }
}