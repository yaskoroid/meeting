<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 06.11.2018
 * Time: 14:17
 */

namespace Service\Core;

use core\Service\ServiceLocator;
use Service\Basic;
use Service\User;
use Service\Context;
use Entity;

class Auth extends Basic
{
    /**
     * @var Context
     */
    private $_contextService;

    /**
     * @var User\Profile
     */
    private $_userProfileService;

    function __construct() {
        $this->_initServices();
    }

    /**
     * @param string $login
     * @param string $password
     * @return bool
     */
    public function auth($login, $password) {
        session_start();
        /** @var Entity\User $user */
        $user = $this->_userProfileService->getUserByLoginAndPassword($login, $password);
        if ($user === null) {
            $this->_deauthentication();
            return false;
        }
        $this->_contextService->setUser($user);
        $this->_setUserAndSessionCookie($user);
        return true;
    }

    public function authBySession() {
        session_start();
        /** @var Entity\User $user */
        $user = $this->_userProfileService->getUserBySessionId($_COOKIE['PHPSESSID']);
        if ($user === null) {
            $this->_deauthentication();
            return;
        }
        $this->_contextService->setUser($user);
    }

    public function deauth() {
        $this->_deauthentication();
    }

    /**
     * @param Entity\User $user
     */
    private function _setUserAndSessionCookie($user) {
        $customizableSessionValues = json_decode($user->customizableSessionValues, true);
        if (!is_array($customizableSessionValues))
            return;
        $expires = time() + 86400 * 365 * 3;
        foreach ($customizableSessionValues as $cookieName=>$value) {
            setcookie($cookieName, $value, $expires, '/');
        }
    }

    private function _deauthentication() {
        $this->_contextService->clearUser();
        /** @var Entity\User $user */
        $user = $this->_contextService->getUser();
        if ($user !== null) {
            $this->_storeUserSessionValues($user);
        }

        // Удаляем переменные сессии
        session_unset();

        // Удаляем сессию
        session_destroy();
    }

    /**
     * @param Entity\User $user
     */
    private function _storeUserSessionValues($user = null) {
        if ($user === null)
            return;

        $sessionCookies = array();

        // Удаляем кастомизированные параметры сессии
        foreach ($_COOKIE as $cookie=>$value) {
            if (strpos($cookie, 'SESSION_') === 0) {
                setcookie($cookie, null, -1, '/');
                $sessionCookies[$cookie] = $value;
            }
        }

        if (count($sessionCookies) > 0) {
            $user->customizableSessionValues = json_encode($sessionCookies, true);
        }
        $user->sessionId = $_COOKIE['PHPSESSID'];

        $this->_userProfileService->storeUser($user);
    }

    private function _initServices() {
        $this->_contextService     = ServiceLocator::contextService();
        $this->_userProfileService = ServiceLocator::userProfileService();
    }
}