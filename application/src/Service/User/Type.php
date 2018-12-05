<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 06.11.2018
 * Time: 18:50
 */

namespace Service\User;

use Service\Basic;
use Service\Repository\Meeting;
use Entity\UserType;
use Entity\User;
use core\Service\ServiceLocator;
use Service\Utils;
use Twig\Node\Expression\Binary\EndsWithBinary;

class Type extends Basic
{
    /**
     * @var Utils
     */
    private $_utilsService;

    /**
     * @var Meeting
     */
    private $_meetingService;

    /**
     * @var Meeting
     */
    private static $_unsecureFields = array('id', 'role', 'description');

    function __construct() {
        self::_initServices();
    }

    private function _initServices() {
        $this->_meetingService = ServiceLocator::repositoryMeetingService();
        $this->_utilsService   = ServiceLocator::utilsService();
    }

    /**
     * @return bool
     */
    public static function isSingleton() {
        return true;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getUsersTypes() {
        /** @var UserType[] */
        static $usersTypes;
        if (is_null($usersTypes)) {
            $usersTypesNotChecked = $this->_meetingService->getUsersTypes();
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
        /** @var UserType[] */
        static $usersTypesSecure;
        if (is_null($usersTypesSecure)) {
            $usersTypesNotSecure = $this->getUsersTypes();
            $usersTypesSecure = array();
            foreach ($usersTypesNotSecure as $userTypeId => $userType)
                foreach ($userType as $userTypeField => $userTypeValue)
                    if (in_array($userTypeField, self::$_unsecureFields))
                        $usersTypesSecure[$userTypeId][$userTypeField] = $userTypeValue;

        }
        return $usersTypesSecure;
    }

    /**
     * @param User $user
     * @return UserType
     */
    public function getUserType($user) {
        $usersTypes = $this->getUsersTypes();

        if (!is_array($usersTypes))
            return null;

        return $this->_utilsService->arrayGetRecursive($usersTypes, array($user->userTypeId));
    }
}