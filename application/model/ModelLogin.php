<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 04.07.2017
 * Time: 13:32
 */

namespace model;

use core\Model;
use core\Service\ServiceLocator;
use Service;

/*
 * Класс модели для отобрадения страницы авторизации
 */
class ModelLogin extends Model
{
    /**
     * @var Service\Auth
     */
    private $_authService;

    function __construct() {
        $this->_initServices();
    }

    // Массив с результатом и метаданными
    private $result = array(
        "page" => "Login",
        "title" => "Авторизация",
        "description" => "Авторизация в приложении",
        "keywords" => "Авторизация, Web приложение"
    );

    // Возвращаем метаданные
    public function getData()
    {
        return $this->result;
    }

    /*
     * Функция проверяет правильно ли введен логин и пароль
     */
    public function login($login = '', $password = '')
    {
        array_merge($this->result, $this->_authService->auth($login, $password)
            ? array(
                'error'    => null,
                'response' => 'You successfully logged in'
            )
            : array(
                'error'    => true,
                'response' => 'Bad login or password'
            )
        );
    }

    private function _initServices() {
        $this->_authService = ServiceLocator::authService();
    }
}