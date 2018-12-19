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

    const USER_CREATE_CONFIRM           = 'user_create_confirm';
    const USER_DELETE_CONFIRM           = 'user_delete_confirm';
    const USER_CHANGE_TYPE_CONFIRM      = 'user_change_type_confirm';
    const USER_CHANGE_PASSWORD_CONFIRM  = 'user_change_password_confirm';
    const USER_CHANGE_EMAIL_OLD_CONFIRM = 'user_change_email_old_confirm';
    const USER_CHANGE_EMAIL_NEW_CONFIRM = 'user_change_email_new_confirm';

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
        require_once $GLOBALS['config']['path']['autoload']['PhpMailer-6.0.6'];
        self::_initServices();
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
     * @param array|null $paramsForTemplate
     * @param string|null $hash
     * @return Entity\Email
     */
    public function create($userEmailTo, $type, array $paramsForTemplate = null, $hash = null) {
        $currentUser = $this->_contextService->getUser();
        if ($currentUser === null)
            throw new \InvalidArgumentException('Context user is not defined');

        $email = new Entity\Email();
        $email->userEmailFrom = $currentUser->email;
        $email->userEmailTo   = $userEmailTo;
        $email->type          = $type;

        $templateParams = $this->_getTemplate($type, $paramsForTemplate);
        $action         = $this->_getEmailAction($templateParams, $hash);

        $this->_setTemplate($email, $templateParams, $action);
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

        $userFrom = $this->_userProfileService->getUserByEmail($email->userEmailFrom);
        $from = '';
        if ($userFrom !== null)
            $from = "{$userFrom->name} {$userFrom->surname}";

        $userTo = $this->_userProfileService->getUserByEmail($email->userEmailTo);
        $to = "{$name} {$surname}";
        if ($userTo !== null)
            $to = "{$userTo->name} {$userTo->surname}";


        $mailer = $this->_getMailer();

        $mailer->setFrom($email->userEmailFrom, $from);
        $mailer->addAddress($email->userEmailTo, $to);
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
     * @param array|null $templateParams
     * @param string|null $action
     */
    private function _setTemplate(&$email, array $templateParams = null, $action = null)
    {

        $style = '';
        $cssFiles = array();
        foreach ($templateParams['styles'] as $css) {
            $cssFile = $css . '.css';
            array_push($cssFiles, $cssFile);
            $style .= file_get_contents(
                $GLOBALS['config']['path']['templates']['css']['email'] . DIRECTORY_SEPARATOR . $cssFile
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
     * @param array $templateParams
     * @param string|null $hash
     * @return string
     * @throws \InvalidArgumentException
     */
    private function _getEmailAction(array $templateParams, $hash) {

        $controller = $templateParams['controller'];
        $action     = $templateParams['action'];
        $get        = $this->_utilsService->arrayGetRecursive($templateParams, array('get'));

        if (!is_string($controller) ||
            !is_string($action) ||
            $controller === '' ||
            $action === '')
            throw new \InvalidArgumentException('Controller and action must be not empty strings');

        if ($get !== null && !is_array($get))
            throw new \InvalidArgumentException('get parameters must be array');

        $result = $GLOBALS['site']['http']
            . "://" . $GLOBALS['site']['domain'] . '/' . strtolower($controller). '/' . strtolower($action);
        if ($hash !== null && is_string($controller) && is_string($action))
            $result .= '?hash=' . $hash;
        if ($get !== null)
            foreach ($get as $key => $value)
                $result .= '&' . strval($key) . '=' . strval($value);

        return $result;
    }

    /**
     * @param $template
     * @param array|null $paramsForTemplate
     * @return mixed
     */
    private function _getTemplate($template, array $paramsForTemplate = null) {
        $templateParams = $this->_getMailTemplate($template, $paramsForTemplate);

        if ($templateParams === null)
            throw new \InvalidArgumentException('Wrong template type');

        return $templateParams;
    }

    /**
     * @param string $template
     * @param array|null $paramsForTemplate
     * @return array
     */
    private function _getMailTemplate($template, array $paramsForTemplate = null) {
        if (empty($paramsForTemplate)) $paramsForTemplate = array();

        if ($template === self::USER_DELETE_CONFIRM)
            return array(
                'file'       => 'change_confirm.tpl',
                'title'      => 'Письмо удаления пользователя на сайте ' . $GLOBALS['site']['domain'],
                'text'       => 'Для подтерждения или отмены удаления аккаунта на сайте ' . $GLOBALS['site']['domain'] .
                    ', перейдите о ссылке',
                'comment'    => 'Email sent to user for confirmation deletion his account',
                'method'     => 'post',
                'controller' => 'confirm',
                'action'     => 'userdeletionconfirmation',
                'styles'     => array(
                    'buttons_v0',
                    'major_v0',
                ),
            );
        elseif ($template === self::USER_CHANGE_TYPE_CONFIRM)
            return array(
                'file'       => 'change_confirm.tpl',
                'title'      => 'Письмо изменения типа аккаунта на сайте ' . $GLOBALS['site']['domain'],
                'text'       => 'Для подтверждения или отмены изменения типа Вашего аккаунта на сайте ' . $GLOBALS['site']['domain'] .
                    ', перейдите о ссылке',
                'comment'    => 'Email sent to user for changing user type',
                'method'     => 'post',
                'controller' => 'confirm',
                'action'     => 'usertypechanging',
                'styles'     => array(
                    'buttons_v0',
                    'major_v0',
                ),
            );
        elseif ($template === self::USER_CHANGE_PASSWORD_CONFIRM)
            return array(
                'file'       => 'change_confirm.tpl',
                'title'      => 'Письмо изменения пароля на сайте ' . $GLOBALS['site']['domain'],
                'text'       => 'Для подтверждения или отмены изменения пароля от аккаунта на сайте ' . $GLOBALS['site']['domain'] .
                    ', перейдите о ссылке',
                'comment'    => 'Email sent to user for changing user password',
                'method'     => 'post',
                'controller' => 'confirm',
                'action'     => 'userpasswordchanging',
                'styles'     => array(
                    'buttons_v0',
                    'major_v0',
                ),
            );
        elseif ($template === self::USER_CREATE_CONFIRM) {
            $login    = $this->_utilsService->arrayGetRecursive($paramsForTemplate, array('login'));
            $imageExt = $this->_utilsService->arrayGetRecursive($paramsForTemplate, array('imageExt'));

            return array(
                'file'       => 'change_confirm.tpl',
                'title'      => 'Письмо подтверждения регистрации на сайте ' . $GLOBALS['site']['domain'],
                'text'       => "Для подтерждения или отмены создания аккаунта '" . $login . "' на сайте " .
                    $GLOBALS['site']['domain'] . ', перейдите о ссылке',
                'comment'    => 'Email sent to user for confirmation his registration',
                'method'     => 'post',
                'controller' => 'confirm',
                'action'     => 'usercreationconfirmation',
                'get'        => array(
                    'login'    => $login,
                    'imageExt' => $imageExt,
                ),
                'styles'     => array(
                    'buttons_v0',
                    'major_v0',
                ),
            );
        } elseif ($template === self::USER_CHANGE_EMAIL_OLD_CONFIRM) {
            $newEmail = $this->_utilsService->arrayGetRecursive($paramsForTemplate, array('newEmail'));
            if ($newEmail === null)
                $newEmail = 'Не могу показать email';

            return array(
                'file'       => 'change_confirm.tpl',
                'title'      => 'Письмо изменения email аккаунта на сайте ' . $GLOBALS['site']['domain'],
                'text'       => 'Для подтерждения или отмены изменения email вашего аккаунта на сайте ' . $GLOBALS['site']['domain'] .
                    ' на другой, перейдите о ссылке. Внимание! После этого, контроль над Вашим аккаунтом ' .
                    "будет остуществляться владельцем email - '$newEmail'" ,
                'comment'    => 'Email sent to old user email for confirmation email changing',
                'method'     => 'post',
                'controller' => 'confirm',
                'action'     => 'userchangeemailrequestconfirmation',
                'styles'     => array(
                    'buttons_v0',
                    'major_v0',
                ),
            );
        } elseif ($template === self::USER_CHANGE_EMAIL_NEW_CONFIRM) {
            $oldEmail = $this->_utilsService->arrayGetRecursive($paramsForTemplate, array('oldEmail'));
            if ($oldEmail === null)
                $oldEmail = 'Не могу показать email';

            return array(
                'file'       => 'change_confirm.tpl',
                'title'      => 'Письмо изменения email аккаунта на сайте ' . $GLOBALS['site']['domain'],
                'text'       => 'Для подтерждения или отмены изменения email вашего аккаунта на сайте ' . $GLOBALS['site']['domain'] .
                    " с '$oldEmail' на этот email, перейдите о ссылке",
                'comment'    => 'Email sent to old user email for confirmation email changing',
                'method'     => 'post',
                'controller' => 'confirm',
                'action'     => 'userchangeemailconfirmation',
                'styles'     => array(
                    'buttons_v0',
                    'major_v0',
                ),
            );
        }
    }
}