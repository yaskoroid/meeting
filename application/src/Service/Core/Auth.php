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
        self::_initServices();
    }

    private function _initServices() {
        $this->_contextService     = ServiceLocator::contextService();
        $this->_userProfileService = ServiceLocator::userProfileService();
    }

    /**
     * @param string $login
     * @param string $password
     * @throws \InvalidArgumentException
     * @return array
     */
    public function auth($login, $password) {
        session_start();
        /** @var Entity\User $user */
        $user = $this->_userProfileService->getUserByLoginAndPassword($login, $password);
        if ($user === null) {
            $this->_deauthentication();
            throw new \InvalidArgumentException('Bad login or password');
        }
        $this->_contextService->setUser($user);

        if (empty($user->sessionId))
            $this->_storeUserSessionValues($user);

        return array(
            'cookies' => $this->_setUserAndSessionCookie($user)
        );
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
     * @return array
     */
    private function _setUserAndSessionCookie($user) {
        $customizableSessionValues = json_decode($user->customizableSessionValues, true);
        $expires = time() + 86400 * 365 * 3;
        $cookies = array();

        $cookies['PHPSESSID'] = array(
            'value'   => $user->sessionId,
            'expires' => $expires,
            'path'    => '/',
        );

        if (!is_array($customizableSessionValues))
            return $cookies;

        foreach ($customizableSessionValues as $cookieName=>$value) {
            $cookies[$cookieName] = array(
                'value'   => $value,
                'expires' => $expires,
                'path'    => '/',
            );
        }

        return $cookies;
    }

    private function _deauthentication() {
        /** @var Entity\User $user */
        $user = $this->_contextService->getUser();
        if ($user !== null) {
            $this->_storeUserSessionValues($user);
        }

        $this->_contextService->clearUser();

        session_unset();

        if (session_id() !== '') session_destroy();
    }

    /**
     * @param Entity\User|null $user
     */
    private function _storeUserSessionValues($user = null) {
        if ($user === null)
            return;

        $sessionCookies = array();

        // Remove custom session values
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
        setcookie('PHPSESSID', null, -1, '/');

        $this->_userProfileService->saveUser($user);
    }
}