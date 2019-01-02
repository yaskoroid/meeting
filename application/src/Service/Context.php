<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 07.11.2018
 * Time: 14:18
 */

namespace Service;

use Entity;

class Context extends Basic {

    /**
     * @var Entity\User
     */
    private $_user;

    /**
     * @return int|null
     */
    public function getUserId() {
        if (!is_null($this->_user)) {
            return $this->_user->id;
        } else {
            return null;
        }
    }

    /**
     * @param Entity\User $user
     */
    public function setUser(Entity\User $user) {
        $this->_user = $user;
    }

    /**
     * @return Entity\User
     */
    public function getUser() {
        return $this->_user;
    }

    public function clearUser() {
        $this->_user = null;
    }

    /**
     * @param callable $callback
     * @param Entity\User $user
     * @return mixed
     * @throws \Exception
     */
    public function executeInUserContext(callable $callback, Entity\User $user) {
        $oldUser = $this->_user;
        try {
            $this->setUser($user);
            $result = $callback($user);
        } catch (\Throwable $t) {
            logThrowable($t);
            $this->_user = $oldUser;
            throw $t;
        } catch (\Exception $e) {
            logException($e);
            $this->_user = $oldUser;
            throw $e;
        }
        $this->_user = $oldUser;
        return $result;
    }

    /**
     * @param callable $callback
     * @return mixed
     * @throws \Exception
     * @throws \Throwable
     */
    public function executeInAnonymousContext(callable $callback) {
        $oldUser = $this->_user;
        try {
            $this->_user = null;
            $result = $callback();
        } catch (\Throwable $t) {
            logThrowable($t);
            $this->_user = $oldUser;
            throw $t;
        } catch (\Exception $e) {
            logException($e);
            $this->_user = $oldUser;
            throw $e;
        }
        $this->_user = $oldUser;
        return $result;
    }

    /**
     * Unique context hash for service's state validation
     * @return string
     */
    public function hash() {
        return "u{$this->getUserId()}";
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->hash();
    }
}