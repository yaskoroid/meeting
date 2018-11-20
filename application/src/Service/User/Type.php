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
use core\Service\ServiceLocator;
use Service\Utils;

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

    function __construct() {
        $this->_initServices();
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
}