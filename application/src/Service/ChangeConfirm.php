<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 20.11.2018
 * Time: 12:37
 */

namespace Service;

use core\Service\ServiceLocator;
use Entity;
use Entity\Factory;
use Service\Basic;
use Service\Repository\Meeting;
use Service;
use Symfony\Component\Process\Exception\LogicException;

class ChangeConfirm extends Basic
{
    const CHANGE_USER_PASSWORD = 'change_user_password';
    const CREATE_USER          = 'create_user';

    /**
     * @var Service\Context
     */
    private $_contextService;

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

    /**
     * @var Service\DateTime
     */
    private $_dateTimeService;

    /**
     * @var Service\Email
     */
    private $_emailService;

    /**
     * @var Service\User\Profile
     */
    private $_userProfileService;

    function __construct() {
        self::_initServices();
    }

    private function _initServices() {
        $this->_contextService     = ServiceLocator::contextService();
        $this->_utilsService       = ServiceLocator::utilsService();
        $this->_meetingService     = ServiceLocator::repositoryMeetingService();
        $this->_permissionService  = ServiceLocator::permissionService();
        $this->_dateTimeService    = ServiceLocator::dateTimeService();
        $this->_emailService       = ServiceLocator::emailService();
        $this->_userProfileService = ServiceLocator::userProfileService();
    }

    /**
     * @param Service\User $newUser
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function createChangeUserCreation($newUser) {
        $hash = $this->_utilsService->createRandomHash128();

        $email = $this->_emailService->create(
            $newUser->email,
            Service\Email::USER_CREATE_CONFIRM,
            $hash
        );

        if (!$this->_emailService->send($email, $newUser->name, $newUser->surname))
            throw new \Exception('Email not sent');

        $this->_removeOld('User');
        $this->_removeAllEntitiesByNewValueWithSameHash('User', 'email', $newUser->email);

        $changesConfirmsForUserCreation = $this->_createChangesConfirmsForUserCreation($newUser, $hash);

        $this->_saveChangesConfirms($changesConfirmsForUserCreation);
    }

    /**
     * @param string $hash
     * @throws \LogicException
     * @throws \Exception
     */
    public function createAfterConfirmUser($hash) {
        $this->_removeOld('User');

        // @TODO find all change fields in db that not expires
        $userCreateChangesConfirms = $this->_findByEntityNameAndFilter('User',
            array(
                'hash' => $hash,
                'dateTimeExpires >=' => $this->_dateTimeService->formatMySqlUtc()
            )
        );

        if ($userCreateChangesConfirms === null)
            throw new \LogicException('No user to create. Confirmation expired already');

        // @TODO create user entity
        $user = $this->_createUserByChangesConfirms($userCreateChangesConfirms);

        // @TODO save user
        $this->_userProfileService->saveUser($user);

        // @TODO delete user fields from db
        $this->_removeAllEntitiesByNewValueWithSameHash('User', 'email', $user->email);

        // @TODO create password new field in db
        $this->createChangeUserPassword($user, $hash);

    }

    /**
     * @param Service\User $user
     * @throws \Exception
     */
    public function createChangeUserPassword($user) {
        $hash = $this->_utilsService->createRandomHash128();

        // @TODO send confirmation email
        $email = $this->_emailService->create(
            $user->email,
            Service\Email::USER_CHANGE_PASSWORD_CONFIRM,
                $hash
            );

        if (!$this->_emailService->send($email, $user->name, $user->surname))
            throw new \Exception('Email not sent');

        $this->_removeOld('User');
        $this->_removeAllEntitiesByEntityIdAndType('User', self::CHANGE_USER_PASSWORD, $user->id);

        $passwordChangeConfirm = $this->_createChangeConfirm(
            $user->id,
            $user->password,
            'User',
            self::CHANGE_USER_PASSWORD,
            'password',
            null,
            $hash,
            'Confirmation of user password changing',
            $this->_dateTimeService->formatMySqlNextHourUtc());

        $this->_saveChangesConfirms(array($passwordChangeConfirm));
    }

    /**
     * @param string $hash
     * @param string $newPassword
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function changeAfterConfirmUserPassword($hash, $newPassword) {
        $this->_removeOld('User');

        $userPasswordChangesConfirms = $this->_findByEntityNameAndFilter('User',
            array(
                'hash' => $hash,
                'dateTimeExpires >=' => $this->_dateTimeService->formatMySqlUtc()
            )
        );

        if ($userPasswordChangesConfirms === null)
            throw new \LogicException('No user to change password. Confirmation expired already');

        $userPasswordChangeConfirm = $userPasswordChangesConfirms[0];

        $newSalt = $this->_utilsService->createSalt();
        $newPassword = $this->_utilsService->createPassword($newPassword, $newSalt);

        $user = $this->_userProfileService->getUserById($userPasswordChangeConfirm->entityId);
        if ($user === null)
            throw new \InvalidArgumentException('No user found for this action');

        $user->salt     = $newSalt;
        $user->password = $newPassword;

        $this->_userProfileService->saveUser($user);

        $this->_removeAllEntitiesByEntityIdAndType('User', self::CHANGE_USER_PASSWORD, $user->id);

    }

    /**
     * @param Service\ChangeConfirm $changeConfirm
     */
    private function _save($changeConfirm) {

    }

    /**
     * @param string $entityName
     * @param array $filter
     */
    private function _removeByEntityNameAndFilter($entityName, $filter) {
        $changesConfirms = $this->_findByEntityNameAndFilter($entityName, $filter);
        if (count($changesConfirms) > 0)
            return;
        $this->_meetingService->removeChangesConfirms($changesConfirms);
    }

    /**
     * @param string $entityName
     * @param array $filter
     * @return Entity\ChangeConfirm[]
     */
    private function _findByEntityNameAndFilter($entityName, $filter) {
        $changesConfirms = $this->_meetingService->getChangeConfirmByEntityNameAndFilter($entityName, $filter);
        array_map(function($changeConfirm) {
            $changeConfirm->entityName = $this->_meetingService->styledProperty($changeConfirm->entityName);
            return $changeConfirm;
        }, $changesConfirms);
        return $changesConfirms;
    }

    /**
     * @param Entity\ChangeConfirm[] $changesConfirms
     */
    private function _saveChangesConfirms($changesConfirms) {
       array_map(function($changeConfirm) {
           $changeConfirm->entityName = $this->_meetingService->realProperty($changeConfirm->entityName);
           return $changeConfirm;
       }, $changesConfirms);
        $this->_meetingService->saveChangesConfirms($changesConfirms);
    }

    /**
     * @param string $entityName
     * @param string $type
     * @param string $field
     * @param string $newValue
     * @param string $hash
     * @param string $comment
     * @param string $dateTimeExpires
     * @return Entity\ChangeConfirm
     */
    private function _createCreateConfirm($entityName, $type, $field, $newValue, $hash, $comment, $dateTimeExpires) {
        $changeConfirm = new Entity\ChangeConfirm();
        $changeConfirm->entityName      = $entityName;
        $changeConfirm->type            = $type;
        $changeConfirm->field           = $field;
        $changeConfirm->newValue        = $newValue;
        $changeConfirm->hash            = $hash;
        $changeConfirm->comment         = $comment;
        $changeConfirm->dateTimeExpires = $dateTimeExpires;
        return $changeConfirm;
    }

    /**
     * @param string $entityId
     * @param string $value
     * @param string $entityName
     * @param string $type
     * @param string $field
     * @param string $newValue
     * @param string $hash
     * @param string $comment
     * @param string $dateTimeExpires
     * @return Entity\ChangeConfirm
     */
    private function _createChangeConfirm(
        $entityId,
        $value,
        $entityName,
        $type,
        $field,
        $newValue,
        $hash,
        $comment,
        $dateTimeExpires
    ) {
        $changeConfirm = $this->_createCreateConfirm(
            $entityName,
            $type,
            $field,
            $newValue,
            $hash,
            $comment,
            $dateTimeExpires
        );

        $changeConfirm->entityId = $entityId;
        $changeConfirm->value    = $value;

        return $changeConfirm;
    }

    /**
     * @param Entity\ChangeConfirm[] $userChangesConfirms
     * @return Entity\User
     * @throws \InvalidArgumentException
     */
    private function _createUserByChangesConfirms($userChangesConfirms) {
        if (!is_array($userChangesConfirms))
            throw new \InvalidArgumentException('User changes confirms must be an array');

        $userFields = array();
        array_map(function($userChangeConfirm) use (&$userFields) {
            $userFields[$userChangeConfirm->field] =
                $this->_utilsService->arrayGetRecursive($userChangeConfirm, array('newValue'));
        }, $userChangesConfirms);

        return Factory\Factory::createEntity($userFields, 'Entity\\User', true);
    }

    /**
     * @param $entityName
     */
    private function _removeOld($entityName) {
        $dateTimeNow = $this->_dateTimeService->formatMySqlUtc();
        $this->_removeByEntityNameAndFilter($entityName,
            array(
                'dateTimeExpires <' => $dateTimeNow
            )
        );
    }

    /**
     * @param string $entityName
     * @param string $field
     * @param string $newValue
     */
    private function _removeAllEntitiesByNewValueWithSameHash($entityName, $field, $newValue) {
        $sameChangesConfirms = $this->_findByEntityNameAndFilter(
            $entityName,
            array(
                'field'    => $field,
                'newValue' => $newValue
            )
        );

        if (count($sameChangesConfirms) < 0)
            return;

        $changesConfirmsHashesToDelete = array();
        array_map(function($sameUserCreationChangeConfirm) use (&$changesConfirmsHashesToDelete) {
            if (!in_array($sameUserCreationChangeConfirm->hash, $changesConfirmsHashesToDelete))
                array_push($changesConfirmsHashesToDelete, $sameUserCreationChangeConfirm->hash);
        }, $sameChangesConfirms);

        if (count($changesConfirmsHashesToDelete) < 0)
            return;

        $this->_meetingService->removeChangesConfirmsByHashes(
            $entityName,
            $changesConfirmsHashesToDelete
        );
    }

    private function _removeAllEntitiesByEntityIdAndType($entityName, $entityId, $type) {

        $foundedChangesConfirms = $this->_findByEntityNameAndFilter(
            $entityName,
            array(
                'entityId' => $entityId,
                'type'     => $type
            )
        );

        if (count($foundedChangesConfirms) < 0)
            return;

        $this->_meetingService->removeChangesConfirms($foundedChangesConfirms);
    }

    /**
     * @param Entity\User $newUser
     * @param string $hash
     * @return Entity\ChangeConfirm[]
     */
    private function _createChangesConfirmsForUserCreation($newUser, $hash) {
        $newUser->salt  = $this->_utilsService->createSalt();
        $newUser->password = $this->_utilsService->createPassword(
            $this->_utilsService->createRandomString(100),
            $newUser->salt
        );

        $result = array();
        foreach ($newUser as $field => $newValue) {
            array_push(
                $result,
                $this->_createCreateConfirm(
                    'User',
                    self::CREATE_USER,
                    $field,
                    $newValue,
                    $hash,
                    'New user creation, waiting while user confirms',
                    $this->_dateTimeService->formatMySqlNextWeekUtc()
                )
            );
        }
        return $result;
    }
}