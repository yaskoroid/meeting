<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 06.11.2018
 * Time: 11:26
 */

namespace Service\Entity;

use Service;
use Entity as Ent;

class User extends Base {

    /**
     * @var array
     */
    public static $entitiesTypes = array(
        'user',
        'customer',
        'administrator'
    );

    function __construct() {
        parent::__construct();
        self::_initServices();
    }

    private function _initServices() {

    }

    /**
     * @param int $sessionId
     * @return Ent\User|null
     */
    public function getUserBySessionId($sessionId) {
        try {
            return $this->_meetingService->getUserBySessionId($sessionId);
        } catch(\Exception $e) {
            logException($e);
            return null;
        }
    }

    /**
     * @return Ent\User
     */
    public function getRandomUser() {
        $result = new Ent\User();
        $result->name = $GLOBALS['config']['admin']['name'];
        $result->surname = $GLOBALS['config']['admin']['surname'];
        $result->email = $GLOBALS['config']['admin']['email'];
        $result->userTypeId = 1;
        $result->login = 'shlabuda';
        $result->password = '';
        $result->salt = '';
        $result->isReady = 1;
        $result->isReadyOnlyForPartnership = 0;
        $result->comment = 'Test, unreal account, you can remove it';
        $result->sex = 1;
        $result->phone = '+380665635421';
        $result->customizableSessionValues = '';
        $result->sessionId = '';
        return $result;
    }

    /**
     * @param Ent\User $user
     */
    /*public function save($user) {
        $this->_meetingService->saveUser($user);
    }*/

    /**
     * @param Ent\User $user
     */
    /*public function deleteUser($user) {
        $this->_meetingService->deleteUser($user);
    }*/

    /**
     * @param string $login
     * @param string $password
     * @return Ent\User|null
     */
    public function getUserByLoginAndPassword($login, $password) {
        /** @var Ent\User */
        $user = null;
        try {
            $user = $this->_meetingService->getUserByLogin($login);
        } catch(\Exception $e) {
            logException($e);
            return null;
        }
        try {
            $this->_utilsService->checkPassword($password, $user->password, $user->salt);
            return $user;
        } catch(\Exception $e) {
            logException($e);
            return null;
        }
        return null;
    }

    /**
     * @param string $search
     * @param string $sortingField
     * @param bool $sortingDirection
     * @param int $pageNumber
     * @param int $usersCountOnPage
     * @param array $userPermissionsForUserRead
     * @return array
     */
    public function getUsersBySearch(
        $search,
        $sortingField,
        $sortingDirection,
        $pageNumber,
        $usersCountOnPage,
        $userPermissionsForUserRead)
    {

        $fieldsToSearchIn = array('name', 'surname', 'email', 'login', 'comment', 'phone');
        $orderBy = $sortingField;
        $direction = $sortingDirection === 'asc';
        $limit = array(
            ($pageNumber - 1) * $usersCountOnPage,
            $usersCountOnPage
        );

        $result = array();
        $result['users'] = $this->_meetingService->getUsersBySearch(
            $fieldsToSearchIn,
            $search,
            $orderBy,
            $direction,
            $limit,
            $userPermissionsForUserRead
        );
        $result['usersCount'] = $this->_meetingService->getAllUsersOfLastSearch();

        return $result;
    }

    /**
     * @param string $login
     * @return Ent\User
     */
    public function getUserByLogin($login) {
        return $this->_meetingService->getUserByLogin($login);
    }

    /**
     * @param string $email
     * @return Ent\User
     */
    public function getUserByEmail($email) {
        return $this->_meetingService->getUserByEmail($email);
    }

    /**
     * @param string $phone
     * @return Ent\User
     */
    public function getUserByPhone($phone) {
        return $this->_meetingService->getUserByPhone($phone);
    }

    /**
     * @param int $id
     * @return Ent\User
     */
    /*public function getById($id) {
        return $this->_meetingService->getUserById($id);
    }*/
}