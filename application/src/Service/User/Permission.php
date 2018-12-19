<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 05.11.2018
 * Time: 14:16
 */

namespace Service\User;

use core\Service\ServiceLocator;
use Service\Basic;
use Service\Repository\Database\Meeting;
use Service\User;
use Service\Utils;
use Service\Context;
use Service;
use Entity;

class Permission extends Basic
{
    /**
     * @var Context
     */
    private $_contextService;

    /**
     * @var User\Type
     */
    private $_userTypeService;

    /**
     * @var Utils
     */
    private $_utilsService;

    /**
     * @var array
     */
    private $_permissionsCrud = array('create', 'update', 'read', 'delete');

    /**
     * @var array
     */
    private $_permissionsUser = array();

    /**
     * @var array
     */
    private $_permissionsSettings = array();

    /**
     * @var array
     */
    private $_permissionsTask = array();

    function __construct() {
        self::_initServices();
        self::_initFields();
    }

    private function _initServices() {
        $this->_userTypeService = ServiceLocator::userTypeService();
        $this->_contextService  = ServiceLocator::contextService();
        $this->_utilsService    = ServiceLocator::utilsService();
    }

    private function _initFields() {
        $this->_permissionsUser     = Service\User\Profile::$entities;
        $this->_permissionsSettings = Service\Settings::$entities;
        $this->_permissionsTask     = Service\Task::$entities;
    }

    /**
     * @param array $permissions
     * @return array
     * @throws \Exception
     */
    public function getPermissions(array $permissions) {
        if (!is_array($permissions))
            throw new \InvalidArgumentException('Permissions must be an array');

        foreach ($permissions as $permission) {
            if (!in_array($permission, $this->_permissionsCrud))
                throw new \InvalidArgumentException('Permissions must be CRUD');
        }

        $user       = $this->_contextService->getUser();
        $usersTypes = $this->_userTypeService->getUsersTypes();
        if (!is_array($usersTypes))
            throw new \InvalidArgumentException('No users types');

        $userType = $this->_utilsService->arrayGetRecursive($usersTypes, array($user->userTypeId));
        if ($userType === null)
            throw new \InvalidArgumentException('User type not found');

        $result = array();
        foreach ($userType as $permission=>$permissionValue) {
            $permissionSubNames = explode('_', $this->_utilsService->camelCaseToUnderline($permission, false));
            if ($permissionSubNames[0] !== 'permission')
                continue;

            if (count($permissionSubNames) < 3)
                throw new \InvalidArgumentException('Bad permission name');

            if (!in_array($permissionSubNames[1], $this->_permissionsCrud))
                continue;

            $firstSubNameValue = $this->_utilsService->arrayGetRecursive($result, array($permissionSubNames[1]));
            if (!is_array($firstSubNameValue))
                $result[$permissionSubNames[1]] = array();

            $permissionName = $permissionSubNames[2] === 'self'
                ? implode('_', array_slice($permissionSubNames, 3))
                : implode('_', array_slice($permissionSubNames, 2));

            $secondSubNameValue = $this->_utilsService->arrayGetRecursive(
                $result,
                array(
                    $permissionSubNames[1],
                    $permissionSubNames[2]
                )
            );
            if ($permissionSubNames[2] === 'self') {
                if (!is_array($secondSubNameValue)) {
                    $result[$permissionSubNames[1]][$permissionSubNames[2]] = array();
                }
                $result[$permissionSubNames[1]][$permissionSubNames[2]][$permissionName] = $permissionValue
                    ? true
                    : false;
                continue;
            }

            $result[$permissionSubNames[1]][$permissionName] = $permissionValue
                ? true
                : false;
        }
        return $result;
    }

    /**
     * @param string $permission
     * @param array $allPermissions
     * @return array
     * @throws \Exception
     */
    public function getUserPermissions($permission, array $allPermissions = array()) {
        if (!is_array($allPermissions))
            throw new \InvalidArgumentException('All user type permissions must be an array');

        if (count($allPermissions) === 0)
            $allPermissions = $this->getPermissions(array($permission));

        $user       = $this->_contextService->getUser();
        $usersTypes = $this->_userTypeService->getUsersTypes();
        if (!is_array($usersTypes))
            throw new \InvalidArgumentException('No users types');

        $permissionsTypes = $this->_utilsService->extractFields(array('id', 'role'), $usersTypes);
        array_push($permissionsTypes, array('id' => $user->id, 'role' => 'self'));

        $userType = $this->_utilsService->arrayGetRecursive($usersTypes, array($user->userTypeId));
        if ($userType === null)
            throw new \InvalidArgumentException('User type not found');

        $result = array();
        foreach ($permissionsTypes as $permissionType) {
            $permissionDetails = array();

            $permissionDetails['permission'] = $permissionType['role'] === 'self'
                ? $this->_utilsService->arrayGetRecursive(
                    $allPermissions,
                    array(
                        $permission,
                        'self',
                        'user'
                    )
                )
                : $this->_utilsService->arrayGetRecursive(
                    $allPermissions,
                    array(
                        $permission,
                        $permissionType['role']
                    )
                );

            if ($permissionDetails['permission'] === null)
                throw new \InvalidArgumentException('Bad user type all permissions, permission was not found');

            $permissionDetails['id'] = $permissionType['id'];
            $result[$permissionType['role']] = $permissionDetails;
        }
        return $result;
    }

    /**
     * @param array $permissions
     * @return array
     */
    public function getUserPermissionsByPermissions(array $permissions) {
        if (!is_array($permissions)) {
            throw new \InvalidArgumentException('Permissions must be an non empty array');
        }
        $allPermissions = $this->getPermissions($permissions);

        foreach ($permissions as $permission) {
            $userPermissionsForUser = $this->getUserPermissions($permission, $allPermissions);
            if (count($userPermissionsForUser) !== 3) {
                throw new \InvalidArgumentException('Not all permissions has been calculated');
            }
            $result[$permission] = $userPermissionsForUser;
        }
        return $result;
    }

    /**
     * @param string $permission
     * @param Entity\User $user
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function getUserPermissionForUser($permission, $user) {
        if (empty($permission)) {
            throw new \InvalidArgumentException('No permission has been sent');
        }

        if (empty($user)) {
            throw new \InvalidArgumentException('No user has been sent');
        }
        $userPermissionsForUsers = $this->getUserPermissions($permission);
        return $this->isHaveUserPermissionForUser($userPermissionsForUsers, $user);
    }

    /**
     * @param array $userPermissions
     * @param Entity\User $user
     * @return bool
     */
    public function isHaveUserPermissionForUser($userPermissions, $user) {
        if ($user->id === $userPermissions['self']['id'])
            return $userPermissions['self']['permission'];

        foreach ($userPermissions as $userType => $permissionDetails)
            if ($user->userTypeId === $permissionDetails['id'])
                return $permissionDetails['permission'];

        return false;
    }

    /**
     * @param Entity\User[] $users
     * @param array $permissions
     * @return array
     */
    public function getUserPermissionsForUsers($users, $permissions) {
        $userPermissionsForUsersByPermission = $this->getUserPermissionsByPermissions($permissions);

        $result = array();
        foreach ($users as $user) {
            foreach ($userPermissionsForUsersByPermission as $userPermissionsForUser => $userPermissionsForUserValue) {
                $result[$user->id][$userPermissionsForUser] =
                    $this->isHaveUserPermissionForUser($userPermissionsForUserValue, $user);
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getUserPermissionsCreateForUsersTypes() {

        $userPermissionsCreateForUsersTypes = $this->getUserPermissions('create');
        if (count($userPermissionsCreateForUsersTypes) !== 3) {
            throw new \InvalidArgumentException('Not all permissions has been calculated');
        }

        $result = array();
        foreach ($userPermissionsCreateForUsersTypes as $userType => $permissionDetails) {
            if ($userType === 'self') continue;
            if ($permissionDetails['permission']) {
                $userTypeDescription = $this->_utilsService->arrayGetRecursive(
                    $this->_userTypeService->getUsersTypes()[$permissionDetails['id']],
                    array('description')
                );
                if ($userTypeDescription === null)
                    continue;

                $result[$permissionDetails['id']] = $userTypeDescription;
            }
        }
        return $result;
    }

    /**
     * @param int $userTypeId
     * @return bool
     */
    public function getUserPermissionCreate($userTypeId) {
        $userPermissionCreateForUser = $this->getUserPermissions('create');

        foreach ($userPermissionCreateForUser as $userType => $permissionDetails) {
            if ($userType === 'self') continue;
            if ($userTypeId === $permissionDetails['id'])
                return $permissionDetails['permission'];
        }
        return false;
    }

    /**
     * @param string $permissionCrud
     * @param string $permission
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function getPermission($permissionCrud, $permission) {
        $permissions = $this->getPermissions(array($permissionCrud));

        $result = $this->_utilsService->arrayGetRecursive(
            $permissions,
            array(
                $permissionCrud,
                'self',
                $permission
            )
        );
        if ($result !== null)
            return $result;

        $result = $this->_utilsService->arrayGetRecursive(
            $permissions,
            array(
                $permissionCrud,
                $permission
            )
        );
        if ($result === null)
            throw new \InvalidArgumentException('Permission was not found, maybe bad user type all permissions');

        return $result;
    }

    /**
     * @return array
     */
    public function getSettingsPermissions() {
        $allCrudPermissions = $this->getPermissions($this->_permissionsCrud);
        foreach($allCrudPermissions as $crudAction=>&$allPermissions) {
            foreach($allPermissions as $action=>&$permission) {
                if ($action !== 'self') {
                    if (!in_array($this->_utilsService->underlineToCamelCase($action), $this->_permissionsSettings))
                        unset($allPermissions[$action]);
                    continue;
                }
                foreach($permission as $actionSelf=>$permissionSelf) {
                    if (!in_array($this->_utilsService->underlineToCamelCase($actionSelf), $this->_permissionsSettings))
                        unset($permission[$actionSelf]);
                }
            }
            if (isset($allPermissions['self']) && count($allPermissions['self']) === 0)
                unset($allPermissions['self']);
        }
        return $allCrudPermissions;
    }
}