<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 04.07.2017
 * Time: 13:32
 */

namespace model;

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
        parent::__construct();
        self::_initServices();
    }

    protected function _initServices() {
        $this->_authService = ServiceLocator::authService();
    }

    public function getData() {
        return $this->_result;
    }

    /**
     * @param array $post
     * @return array
     */
    protected function _getLogin(array $post)
    {
        $result = $this->_authService->auth($post['login'], $post['password']);
        return array_merge(
            $result,
            array(
                'text' => 'You successfully logged in',
            )
        );
    }
}