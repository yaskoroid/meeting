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
use PhpImap;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email extends Basic
{
    const THROW_EXCEPTIONS = true;

    const USER_CREATE_CONFIRM          = 'user_create_confirm';
    const USER_CHANGE_PASSWORD_CONFIRM = 'user_change_password_confirm';

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

    /**
     * @var Service\Context
     */
    private $_contextService;

    function __construct() {
        require_once $GLOBALS['config']['autoload']['PhpMailer-6.0.6'];
        $this->_initServices();
    }

    private function _initServices() {
        $this->_utilsService       = ServiceLocator::utilsService();
        $this->_meetingService     = ServiceLocator::repositoryMeetingService();
        $this->_userProfileService = ServiceLocator::userProfileService();
        $this->_dateTimeService    = ServiceLocator::dateTimeService();
        $this->_templateService    = ServiceLocator::templateService();
        $this->_contextService     = ServiceLocator::contextService();
    }

    /**
     * @param string $userEmailTo
     * @param string $type
     * @param string|null $hash
     * @return Entity\Email
     */
    public function create($userEmailTo, $type, $hash = null) {
        $currentUser = $this->_contextService->getUser();
        if ($currentUser === null)
            throw new \InvalidArgumentException('Context uer is not defined');

        $email = new Entity\Email();
        $email->userEmailFrom = $currentUser->email;
        $email->userEmailTo   = $userEmailTo;
        $email->type          = $type;

        $action = $this->_getEmailAction($type, $hash);

        $this->_setTemplate($email, $type, $action);
        return $email;
    }

    /**
     * @param Entity\Email $email
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function send($email, $name = null, $surname = null) {

        if (empty($email->userEmailFrom) || empty($email->userEmailTo))
            throw new \InvalidArgumentException('Email user send to or user send from is empty');

        $userFromName = $userFromSurname = '';
        $userFrom = $this->_userProfileService->getUserByEmail($email->userEmailFrom);
        if ($userFrom !== null) {
            $userFromName    = $userFrom->name;
            $userFromSurname = $userFrom->surname;
        }

        $userTo = $this->_userProfileService->getUserByEmail($email->userEmailTo);
        if ($userTo !== null) {
            $name    = $userTo->name;
            $surname = $userTo->surname;
        }

        $mailer = $this->_getMailer();

        $mailer->setFrom($email->userEmailFrom, $userFromName . $userFromSurname);
        $mailer->addAddress($email->userEmailTo, $name . $surname);
        $mailer->Subject  = $email->title;
        $mailer->Body     = $email->body;
        $this->monitoringStop('_sendEmail', 'EmailService');

        $email->isAccepted = $mailer->send();
        $this->_save($email);

        return $email->isAccepted;
    }

    /**
     * @return \PHPMailer
     */
    private function _getMailer() {
        $this->monitoringStart('_sendEmail', 'EmailService');
        $smtpHost = $GLOBALS['config']['email_service.smtp.host'];
        $smtpPort = $GLOBALS['config']['email_service.smtp.port'];
        $smtpUser = $GLOBALS['config']['email_service.smtp.user'];
        $smtpPass = $GLOBALS['config']['email_service.smtp.password'];

        $mail = new PHPMailer(self::THROW_EXCEPTIONS);
        $mail->isSMTP();
        $mail->CharSet    = 'UTF-8';
        $mail->Host       = $smtpHost;
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = $smtpPort;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpUser;
        $mail->Password   = $smtpPass;
        $mail->isHTML(true);
        return $mail;
    }

    private function _createEmail() {

    }

    /**
     * @param Entity\Email $email
     */
    private function _save($email) {
        $this->monitoringStart('_saveEmail', 'EmailService');
        $this->_meetingService->saveEmail($email);
        $this->monitoringStop('_saveEmail', 'EmailService');
    }

    /**
     * @param Entity\Email $email
     * @param string $type
     * @param string|null $action
     */
    private function _setTemplate(&$email, $type, $action = null)
    {
        $templateParams = $this->_getTemplate($type);
        $style = '';
        $cssFiles = array();
        foreach ($templateParams['styles'] as $css) {
            $cssFile = $css . '.css';
            array_push($cssFiles, $cssFile);
            $style .= file_get_contents(
                $GLOBALS['config']['paths']['templates']['css']['email'] . DIRECTORY_SEPARATOR . $cssFile
            );
        }
        $email->title = $templateParams['title'];

        if ($action !== null )
        $templateParams['action'] = $action;

        $email->body = $this->_templateService->render($templateParams['file'], $templateParams, 'email');

        $email->css = implode(',', $cssFiles);
        $email->comment = $templateParams['comment'];
        $email->dateTime = $this->_dateTimeService->formatMySqlUtc();
    }

    /**
     * @param string $type
     * @param string|null $hash
     * @return string
     * @throws \InvalidArgumentException
     */
    private function _getEmailAction($type, $hash) {
        $templateParams = $this->_getTemplate($type);

        $controller = $templateParams['controller'];
        $action     = $templateParams['action'];

        if (!is_string($controller) ||
            !is_string($action) ||
            $controller === '' ||
            $action === '')
            throw new \InvalidArgumentException('Controller and action must be not empty strings');

        $result = $GLOBALS['site']['http']
        . "://" . $GLOBALS['site']['domain'] . '/' . strtolower($controller). '/' . strtolower($action);
        if ($hash !== null && is_string($controller) && is_string($action))
            $result .= '?hash=' . $hash;

        return $result;
    }

    private function _getTemplate($template) {
        static $templates;
        if (is_null($templates))
            $templates = $this->_getMailTemplates();

        $templateParams = $this->_utilsService->arrayGetRecursive($templates, array($template));
        if ($templateParams === null)
            throw new \InvalidArgumentException('Wrong template type');

        return $templateParams;
    }

    private function _getMailTemplates() {
        return array(
            self::USER_CREATE_CONFIRM => array(
                'file'       => 'user_create_confirm.tpl',
                'title'      => 'Письмо подтверждения регистрации',
                'text'       => 'Для подтерждения создания аккаунта на сайте ' . $GLOBALS['site']['domain'] .
                    ', нажмите на кнопку или перейдите о ссылке',
                'comment'    => 'Email sent to user for confirmation his registration',
                'method'     => 'post',
                'controller' => 'confirm',
                'action'     => 'usercreation',
                'styles'     => array(
                    'buttons_v0',
                    'major_v0',
                ),
            ),
            self::USER_CHANGE_PASSWORD_CONFIRM => array(
                'file'       => 'user_change_password_confirm.tpl',
                'title'      => 'Письмо изменения пароля',
                'text'       => 'Для изменения пароля от аккаунта на сайте ' . $GLOBALS['site']['domain'] .
                    ', нажмите на кнопку или перейдите о ссылке',
                'comment'    => 'Email sent to user for changing user password',
                'method'     => 'post',
                'controller' => 'confirm',
                'action'     => 'passwordchange',
                'styles'     => array(
                    'buttons_v0',
                    'major_v0',
                ),
            ),
        );
    }
}