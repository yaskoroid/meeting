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
    private $_result = array(
        'page'        => 'Login',
        'title'       => 'Авторизация',
        'description' => 'Авторизация в приложении',
        'keywords'    => 'Авторизация, Web приложение'
    );

    function __construct() {
        $this->_initServices();
    }

    private function _initServices() {
        $this->_authService = ServiceLocator::authService();
    }

    public function getData()
    {
        return $this->_result;
    }

    /**
     * @param string $login
     * @param string $password
     * @return array
     */
    public function login($login = '', $password = '')
    {
        return array_merge($this->_result, $this->_authService->auth($login, $password)
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