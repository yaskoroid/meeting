<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 20.11.2018
 * Time: 12:37
 */

namespace Service;

use core\Service\ServiceLocator;
use component;
use Entity;
use Service\Repository\Meeting;
use Service;

class Email extends Basic
{
    const USER_CREATE_CONFIRM = 'user_create_confirm';

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

    /**
     * @var Service\Template
     */
    private $_templateService;

    function __construct() {
        $this->_initServices();
    }

    private function _initServices() {
        $this->_utilsService       = ServiceLocator::utilsService();
        $this->_meetingService     = ServiceLocator::repositoryMeetingService();
        $this->_userProfileService = ServiceLocator::userProfileService();
        $this->_dateTimeService    = ServiceLocator::dateTimeService();
        $this->_templateService    = ServiceLocator::templateService();
    }

    /**
     * @param string $userIdFrom
     * @param string $userIdTo
     * @param string $type
     */
    public function create($userIdFrom, $userIdTo, $type) {

        $email = new Entity\Email();
        $email->body = $this->_getTemplate($userIdFrom, $userIdTo, $type);
        return $email->body;
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

    private function _getTemplate() {
        $templates = array(
            self::USER_CREATE_CONFIRM => 'user_create_confirm.tpl',
        );
        $style = '';
        foreach (array('buttons_v0.css', 'major_v0.css') as $css) {
            $style .= file_get_contents($GLOBALS['config']['paths']['templates']['css']['email'] . DIRECTORY_SEPARATOR . $css);
        }
        $params = array(
            'title' => 'Письмо подтверждения регистрации',
            'method' => 'post',
            'action' => $GLOBALS['site']['http'] . "://" . $GLOBALS['site']['domain'] . '/confirm/email?hash=sdfgsdfgsdf',
            'text' => 'Для подтерждения создания аккаунта на сайте, нажмите на кнопку или перейдите о ссылке',
            'style' => $style
        );
        return $this->_templateService->render($templates[self::USER_CREATE_CONFIRM], $params, 'email');
    }
}