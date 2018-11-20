<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 20.11.2018
 * Time: 12:37
 */

namespace Service;

use core\Service\ServiceLocator;
use component\Email;
use Entity;
use Service\Repository\Meeting;
use Service;

class Email extends Basic
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
     * @var Service\User\Profile
     */
    private $_userProfileService;

    /**
     * @var Service\DateTime
     */
    private $_dateTimeService;

    function __construct() {
        $this->_initServices();
    }

    private function _initServices() {
        $this->_utilsService       = ServiceLocator::utilsService();
        $this->_meetingService     = ServiceLocator::repositoryMeetingService();
        $this->_userProfileService = ServiceLocator::userProfileService();
        $this->_dateTimeService    = ServiceLocator::dateTimeService();
    }

    /**
     * @return Entity\Email
     */
    public function create($userIdFrom, $userIdTo, ) {

    }

    /**
     * @param Entity\Email $email
     * @return bool
     */
    public function send($email) {

        $userFrom = $this->_userProfileService->getUserById($email->userIdFrom);
        $userTo = $this->_userProfileService->getUserById($email->userIdTo);

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        $headers[] = "To: $userTo->name  $userTo->surname <$userTo->email>";
        $headers[] = "From: $userFrom->name $userFrom->surname <$userFrom->email>";
        $headers[] = "Cc: $userFrom->email";
        $headers[] = "Bcc: $userFrom->email";

        $email->isAccepted = mail($userFrom->email, $email->title, $email->body, $headers);
        $email->dateTime   = $this->_dateTimeService->formatMySql($this->_dateTimeService->now());

        $this->_save($email);

        return $email->isAccepted;
    }

    private function _createEmail() {

    }

    /**
     * @param Entity\Email $email
     */
    private function _save($email) {
        $this->_meetingService->saveEmail($email);
    }
}