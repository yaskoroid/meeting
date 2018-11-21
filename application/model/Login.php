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

class Login extends Model
{
    /**
     * @var Service\Auth
     */
    private $_authService;

    /**
     * @var array
     */
    private $result = array(
        "page" => "Login",
        "title" => "Авторизация",
        "description" => "Авторизация в приложении",
        "keywords" => "Авторизация, Web приложение"
    );

    function __construct() {
        $this->_initServices();
    }

    private function _initServices() {
        $this->_authService = ServiceLocator::authService();
    }

    public function getData()
    {
        return $this->result;
    }

    /**
     * @param string $login
     * @param string $password
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

}