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
    private $_forUserPermissionsCrud = array('create', 'update', 'read', 'delete');

    /**
     * @var array
     */
    private $_forUserPermissionsCreate = array('create');

    function __construct() {
        $this->_initServices();
    }

    private function _initServices() {
        $this->_userTypeService    = ServiceLocator::userTypeService();
        $this->_contextService     = ServiceLocator::contextService();
        $this->_utilsService       = ServiceLocator::utilsService();
    }

    /**
     * @param array $localPermissions
     * @param bool|false $isCreate
     * @return array
     * @throws \Exception
     */
    public function getPermissionsForUserTypesAndSelf(array $localPermissions, $isCreate = false) {
        if (!is_array($localPermissions))
            throw new \InvalidArgumentException('For user permissions must be an array');

        foreach ($localPermissions as $localPermission) {
            $forUserPermissions = $isCreate ? $this->_forUserPermissionsCreate : $this->_forUserPermissionsCrud;
            if (!in_array($localPermission, $forUserPermissions))
                throw new \InvalidArgumentException('For user permissions must be CRUD');
        }

        $user = $this->_contextService->getUser();
        $usersTypes = $this->_userTypeService->getUsersTypes();
        if (!is_array($usersTypes))
            throw new \InvalidArgumentException('No users types');

        $forUserPermissionsTypes = $this->_utilsService->extractFields(array('id', 'role'), $usersTypes);
        array_push($forUserPermissionsTypes, array('id' => $user->id, 'role' => 'self'));

        $userType = $this->_utilsService->arrayGetRecursive($usersTypes, array($user->userTypeId));
        if ($userType === null)
            throw new \InvalidArgumentException('User type not found');

        $result = array();
        foreach ($forUserPermissionsTypes as $forUserPermissionType) {
            foreach ($localPermissions as $localPermission) {
                $permissionName = 'permissionForUser' . ucfirst($localPermission) . ucfirst($forUserPermissionType['role']);

                $permissionDetails = array();
                $permissionDetails['id'] = $forUserPermissionType['id'];

                $userType->{$permissionName}
                    ? $permissionDetails['permission'] = true
                    : $permissionDetails['permission'] = false;

                $result[$forUserPermissionType['role']] = $permissionDetails;
            }
        }
        return $result;
    }

    /**
     * @param array $permissions
     * @return array
     */
    public function getPermissionsForUserTypesAndSelfByPermissions(array $permissions) {
        if (!is_array($permissions)) {
            throw new \InvalidArgumentException('Permissions must be an non empty erray');
        }
        foreach ($permissions as $permission) {
            $permissionsForUser = $this->getPermissionsForUserTypesAndSelf(array($permission));
            if (count($permissionsForUser) !== 3) {
                throw new \InvalidArgumentException('Not all permissions has been calculated');
            }
            $result[$permission] = $permissionsForUser;
        }
        return $result;
    }

    /**
     * @param string $permission
     * @param Entity\User $user
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function getPermissionForUser($permission, $user) {
        if (empty($permission)) {
            throw new \InvalidArgumentException('No permission has been sent');
        }

        if (empty($user)) {
            throw new \InvalidArgumentException('No user has been sent');
        }
        $permissionsUsersFor = $this->getPermissionsForUserTypesAndSelf(array($permission));
        return $this->isHavePermissionForUser($permissionsUsersFor, $user);
    }

    /**
     * @param array $permissionsForUsers
     * @param Entity\User $user
     * @return bool
     */
    public function isHavePermissionForUser($permissionsForUsers, $user) {
        if ($user->id === $permissionsForUsers['self']['id'])
            return $permissionsForUsers['self']['permission'];

        foreach ($permissionsForUsers as $userType => $permissionDetails)
            if ($user->userTypeId === $permissionDetails['id'])
                return $permissionDetails['permission'];

        return false;
    }

    /**
     * @param Entity\User[] $users
     * @param array $permissions
     * @return array
     */
    public function getPermissionsForUsers($users, $permissions) {
        $permissionsForUsersByPermission = $this->getPermissionsForUserTypesAndSelfByPermissions($permissions);

        $result = array();
        foreach ($users as $user) {
            foreach ($permissionsForUsersByPermission as $permissionForUser => $permissionsForUsersValue) {
                $result[$user->id][$permissionForUser] =
                    $this->isHavePermissionForUser($permissionsForUsersValue, $user);
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getPermissionsForUsersTypeCreate() {

        $permissionsForUserCreate = $this->getPermissionsForUserTypesAndSelf(array('create'));
        if (count($permissionsForUserCreate) !== 3) {
            throw new \InvalidArgumentException('Not all permissions has been calculated');
        }

        $result = array();
        foreach ($permissionsForUserCreate as $forUser => $permissionDetails) {
            if ($permissionDetails['permission'] && $forUser !== 'self') {
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
    public function getPermissionForUserCreate($userTypeId) {
        $permissionUserForCreate = $this->getPermissionsForUserTypesAndSelf(array('create'), true);

        foreach ($permissionUserForCreate as $userType => $permissionDetails)
            if ($userType === 'self') continue;
            if ($userTypeId === $permissionDetails['id'])
                return $permissionDetails['permission'];
        return false;
    }

    /**
     * @param Entity\User[] $usersRequiringPermission
     * @param Entity\User[] $usersToWorkOn
     * @param array $permissionsCrud
     * @throws \Exception
     */
    public function checkUsersForUsersPermissions(array $usersRequiringPermission, array $usersToWorkOn, array $permissionsCrud) {

        if (!is_array($usersRequiringPermission)) {
            throw new \Exception('Users requiring permission must be array');
        }

        if (!is_array($usersToWorkOn)) {
            throw new \Exception('Users to work on must be array');
        }

        if (!is_array($permissionsCrud)) {
            throw new \Exception('Permissions must be array');
        }

        if (!$this->_utilsService->isArrayValuesInAnotherArray($permissionsCrud, self::$_etalonPermissionCrud)) {
            throw new \Exception('Wrong array of permissions to check users to work on');
        }

        $usersTypes = $this->_userTypeService->getUsersTypes();


        $isSelf = $authUser->id === $forUser->id;
    }
}