<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 06.11.2018
 * Time: 11:26
 */

namespace Service\User;

use core\Service\ServiceLocator;
use Entity;
use Service\Basic;
use Service\Repository\Meeting;
use Service;

class Profile extends Basic
{
    /**
     * @var Service\Utils
     */
    private $_utilsService;

    /**
     * @var Meeting
     */
    private $_meetingService;

    /**
     * @var Meeting
     */
    private $_permissionService;

    private $_secureUserFields = array(
        'password',
        'salt',
        'customizableSessionValues',
        'sessionId',
    );

    function __construct() {
        $this->_initServices();
    }

    private function _initServices() {
        $this->_utilsService      = ServiceLocator::utilsService();
        $this->_meetingService    = ServiceLocator::repositoryMeetingService();
        $this->_permissionService = ServiceLocator::permissionService();
    }

    public function getUserBySessionId($sessionId)
    {
        try {
            return $this->_meetingService->getUserBySessionId($sessionId);
        } catch(\Exception $e) {
            logException($e);
            return null;
        }
    }

    /**
     * @return Entity\User
     */
    public function getRandomUser() {
        $result = new Entity\User();
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

    public function createUser() {

    }

    /**
     * @param Entity\User $user
     */
    public function saveUser($user) {
        $this->_meetingService->saveUser($user);
    }

    /**
     * @param Entity\User $user
     */
    public function deleteUser($user) {
        $this->_meetingService->deleteUserById($user->id);
    }

    /**
     * @param string $login
     * @param string $password
     * @return Entity\User|null
     */
    public function getUserByLoginAndPassword($login, $password) {
        /** @var Entity\User */
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
     * @param array $permissionsUserFor
     * @return array
     */
    public function getUsersBySearch($search, $sortingField, $sortingDirection, $pageNumber, $usersCountOnPage, $permissionsUserFor) {

        $fieldsToSearchIn = array('name', 'surname', 'email', 'login', 'comment', 'phone');
        $orderBy = $sortingField;
        $direction = $sortingDirection === 'asc';
        $limit = array(
            ($pageNumber - 1) * $usersCountOnPage,
            $usersCountOnPage
        );

        $result = array();
        $result['users'] = $this->_meetingService->getUsersBySearch($fieldsToSearchIn, $search, $orderBy, $direction, $limit, $permissionsUserFor);
        $result['usersCount'] = $this->_meetingService->getAllUsersOfLastSearch();

        return $result;
    }

    /**
     * @param string $login
     * @return Entity\User
     */
    public function getUserByLogin($login) {
        return $this->_meetingService->getUserByLogin($login);
    }

    /**
     * @param string $email
     * @return Entity\User
     */
    public function getUserByEmail($email) {
        return $this->_meetingService->getUserByEmail($email);
    }

    /**
     * @param int $id
     * @return Entity\User
     */
    public function getUserById($id) {
        return $this->_meetingService->getUserById($id);
    }

    /**
     * @param Entity\User[] $users
     */
    public function filterSecureUsersFields(&$users) {
        foreach ($users as $user) {
            $this->filterSecureUserFields($user);
        }
    }

    /**
     * @param Entity\User $user
     */
    public function filterSecureUserFields(&$user) {
        foreach ($this->_secureUserFields as $secureUserField) {
            unset($user->{$secureUserField});
        }
    }
}