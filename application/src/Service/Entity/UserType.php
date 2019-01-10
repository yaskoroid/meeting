<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 06.11.2018
 * Time: 18:50
 */

namespace Service\Entity;

use Service;
use Entity as Ent;

class UserType extends Base {

    function __construct() {
        parent::__construct();
        self::_initServices();
    }

    private function _initServices() {

    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getUsersTypes() {
        /** @var Ent\UserType[] */
        static $usersTypes;
        if (is_null($usersTypes)) {
            $usersTypesNotChecked = $this->get('UserType');
            if (!is_array($usersTypesNotChecked)) {
                throw new \Exception('No users types');
            }
            $usersTypes = $this->_utilsService->buildIndex($usersTypesNotChecked);
        }
        return $usersTypes;
    }

    /**
     * @return array
     */
    public function getUsersTypesSecure() {
        /** @var Ent\UserType[] */
        static $usersTypesSecure;
        if (is_null($usersTypesSecure)) {
            $usersTypesNotSecure = $this->getUsersTypes();
            $usersTypesSecure = array();
            $publicFields = $this->getEntityPublicFields($this->_getClass());
            foreach ($usersTypesNotSecure as $userTypeId => $userType)
                foreach ($userType as $userTypeField => $userTypeValue)
                    if (in_array($userTypeField, $publicFields))
                        $usersTypesSecure[$userTypeId][$userTypeField] = $userTypeValue;

        }
        return $usersTypesSecure;
    }

    /**
     * @param Ent\User $user
     * @return Ent\UserType
     */
    public function getUserType($user) {
        if ($user === null)
            return null;

        $usersTypes = $this->getUsersTypes();

        if (!is_array($usersTypes))
            return null;

        return $this->_utilsService->arrayGetRecursive($usersTypes, array($user->userTypeId));
    }
}