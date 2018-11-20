<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 20.11.2018
 * Time: 12:37
 */

namespace Service\User;

use core\Service\ServiceLocator;
use Entity;
use Service\Basic;
use Service\Repository\Meeting;
use Service;

class ChangeConfirm extends Basic
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

    function __construct() {
        $this->_initServices();
    }

    private function _initServices() {
        $this->_utilsService      = ServiceLocator::utilsService();
        $this->_meetingService    = ServiceLocator::repositoryMeetingService();
        $this->_permissionService = ServiceLocator::permissionService();
    }

    /**
     * @param Service\User\ChangeConfirm $userChangeConfirm
     */
    public function save($userChangeConfirm) {
        $this->_meetingService->saveUserChangeConfirm($userChangeConfirm);
    }
}