<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 20.11.2018
 * Time: 12:37
 */

namespace Service\Entity;

use core\Service\ServiceLocator;
use Service;
use Entity as Ent;
use model\Def;

class ChangeConfirm extends Base {
    const CREATE_USER               = 'create_user';
    const DELETE_USER               = 'delete_user';
    const CHANGE_USER_TYPE          = 'change_user_type';
    const CHANGE_USER_PASSWORD      = 'change_user_password';
    const CHANGE_USER_EMAIL_REQUEST = 'change_user_email_request';
    const CHANGE_USER_EMAIL         = 'change_user_email';

    /**
     * @var Service\Context
     */
    private $_contextService;

    /**
     * @var Service\Permission
     */
    private $_permissionService;

    /**
     * @var Service\DateTime
     */
    private $_dateTimeService;

    /**
     * @var Service\Entity\Email
     */
    private $_emailService;

    /**
     * @var Service\Entity\User
     */
    private $_userService;

    /**
     * @var Service\Path
     */
    private $_pathService;

    /**
     * @var Service\Downloader
     */
    private $_downloaderService;

    /**
     * @var Service\Entity\File
     */
    private $_fileService;

    function __construct() {
        parent::__construct();
        self::_initServices();
    }

    private function _initServices() {
        $this->_contextService    = ServiceLocator::contextService();
        $this->_permissionService = ServiceLocator::permissionService();
        $this->_dateTimeService   = ServiceLocator::dateTimeService();
        $this->_emailService      = ServiceLocator::emailService();
        $this->_userService       = ServiceLocator::userService();
        $this->_pathService       = ServiceLocator::pathService();
        $this->_downloaderService = ServiceLocator::downloaderService();
        $this->_fileService       = ServiceLocator::fileService();
    }

    /**
     * @param Ent\User $newUser
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function createChangeUserCreation($newUser) {
        $hash = $this->_utilsService->createRandomHash128();

        $email = $this->_emailService->create(
            $newUser->email,
            Service\Entity\Email::USER_CREATE_CONFIRM,
            null,
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
     * @throws \RuntimeException
     */
    public function createAfterConfirmUser($hash) {
        $this->_removeOld('User');

        $userCreateChangesConfirms = $this->_findByEntityNameAndFilter('User',
            array(
                'type'               => self::CREATE_USER,
                'hash'               => $hash,
                'dateTimeExpires >=' => $this->_dateTimeService->formatMySqlUtc() . ' UTC'
            )
        );

        if (empty($userCreateChangesConfirms))
            throw new \LogicException('Confirmation user creation already expired or removed');

        $user = $this->_createUserByChangesConfirms($userCreateChangesConfirms);

        $this->_removeAllEntitiesByNewValueWithSameHash('User', 'email', $user->email);

        $this->_userService->save($user);

        $this->_contextService->executeInUserContext(function() use ($user, $hash){
            $this->createChangeUserPassword($user, $hash);
        }, $user);

        if ($user->imageFileId !== null)
            $this->_downloaderService->storeFromTemp($user->imageFileId);
    }

    /**
     * @param Ent\User $user
     * @throws \Exception
     */
    public function createChangeUserDelete($user) {
        $this->_checkExitingActualChangesConfirms('User', $user->id, self::DELETE_USER);

        $hash = $this->_utilsService->createRandomHash128();

        $email = $this->_emailService->create(
            $user->email,
            Service\Entity\Email::USER_DELETE_CONFIRM,
            null,
            $hash
        );

        if (!$this->_emailService->send($email, $user->name, $user->surname))
            throw new \Exception('Email not sent');

        $this->_removeOld('User');
        $this->_removeAllEntitiesByEntityIdAndType('User', self::DELETE_USER, $user->id);

        $userDeleteChangeConfirm = $this->_createChangeConfirm(
            $user->id,
            null,
            'User',
            self::DELETE_USER,
            null,
            null,
            $hash,
            'Confirmation of user deletion',
            $this->_dateTimeService->formatMySqlNextHourUtc());

        $this->_saveChangesConfirms(array($userDeleteChangeConfirm));
    }

    /**
     * @param string $hash
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function changeAfterConfirmUserDelete($hash) {
        $this->_removeOld('User');

        $userDeleteChangesConfirms = $this->_findByEntityNameAndFilter('User',
            array(
                'type'               => self::DELETE_USER,
                'hash'               => $hash,
                'dateTimeExpires >=' => $this->_dateTimeService->formatMySqlUtc() . ' UTC'
            )
        );

        if (empty($userDeleteChangesConfirms))
            throw new \LogicException('Confirmation user deletion already expired or removed');

        $userDeleteChangeConfirm = $userDeleteChangesConfirms[0];

        $user = $this->_userService->getById($userDeleteChangeConfirm->entityId);
        if ($user === null)
            throw new \InvalidArgumentException('No user found for this action');

        $imageFileId = $user->imageFileId;

        $this->_userService->delete($user);

        if ($imageFileId !== null)
            $this->_fileService->deleteByIdsAndStorage(array($imageFileId));

        $this->_removeAllEntitiesByEntityIdAndType('User', self::DELETE_USER, $user->id);
    }

    /**
     * @param Ent\User $user
     * @param string $userTypeId
     * @throws \Exception
     */
    public function createChangeUserType($user, $userTypeId) {
        $this->_checkExitingActualChangesConfirms('User', $user->id, self::CHANGE_USER_TYPE);

        $hash = $this->_utilsService->createRandomHash128();

        $email = $this->_emailService->create(
            $user->email,
            Service\Entity\Email::USER_CHANGE_TYPE_CONFIRM,
            null,
            $hash
        );

        if (!$this->_emailService->send($email, $user->name, $user->surname))
            throw new \Exception('Email not sent');

        $this->_removeOld('User');
        $this->_removeAllEntitiesByEntityIdAndType('User', self::CHANGE_USER_TYPE, $user->id);

        $typeChangeConfirm = $this->_createChangeConfirm(
            $user->id,
            $user->userTypeId,
            'User',
            self::CHANGE_USER_TYPE,
            'user_type_id',
            intval($userTypeId),
            $hash,
            'Confirmation of user type changing',
            $this->_dateTimeService->formatMySqlNextHourUtc());

        $this->_saveChangesConfirms(array($typeChangeConfirm));
    }

    /**
     * @param string $hash
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function changeAfterConfirmUserType($hash) {
        $this->_removeOld('User');

        $userPasswordChangesConfirms = $this->_findByEntityNameAndFilter('User',
            array(
                'type'               => self::CHANGE_USER_TYPE,
                'hash'               => $hash,
                'dateTimeExpires >=' => $this->_dateTimeService->formatMySqlUtc() . ' UTC'
            )
        );

        if (empty($userPasswordChangesConfirms))
            throw new \LogicException('Confirmation user type changing already expired or removed');

        $userPasswordChangeConfirm = $userPasswordChangesConfirms[0];

        $user = $this->_userService->getById($userPasswordChangeConfirm->entityId);
        if ($user === null)
            throw new \InvalidArgumentException('No user found for this action');

        $user->userTypeId = $userPasswordChangeConfirm->newValue;

        $this->_userService->save($user);

        $this->_removeAllEntitiesByEntityIdAndType('User', self::CHANGE_USER_TYPE, $user->id);
    }

    /**
     * @param Ent\User $user
     * @throws \Exception
     */
    public function createChangeUserPassword($user) {
        $this->_checkExitingActualChangesConfirms('User', $user->id, self::CHANGE_USER_PASSWORD);

        $hash = $this->_utilsService->createRandomHash128();

        $email = $this->_emailService->create(
            $user->email,
            Service\Entity\Email::USER_CHANGE_PASSWORD_CONFIRM,
            null,
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
                'type'               => self::CHANGE_USER_PASSWORD,
                'hash'               => $hash,
                'dateTimeExpires >=' => $this->_dateTimeService->formatMySqlUtc() . ' UTC'
            )
        );

        if (empty($userPasswordChangesConfirms))
            throw new \LogicException('Confirmation user password changing already expired or removed');

        $userPasswordChangeConfirm = $userPasswordChangesConfirms[0];

        $newSalt     = $this->_utilsService->createSalt();
        $newPassword = $this->_utilsService->createPassword($newPassword, $newSalt);

        $user = $this->_userService->getById($userPasswordChangeConfirm->entityId);
        if ($user === null)
            throw new \InvalidArgumentException('No user found for this action');

        $user->salt     = $newSalt;
        $user->password = $newPassword;

        $this->_userService->save($user);

        $this->_removeAllEntitiesByEntityIdAndType('User', self::CHANGE_USER_PASSWORD, $user->id);

    }

    /**
     * @param Ent\User $user
     * @param string $newEmail
     * @throws \Exception
     */
    public function createChangeUserEmailRequest($user, $newEmail) {
        $this->_checkExitingActualChangesConfirms('User', $user->id, self::CHANGE_USER_EMAIL_REQUEST);

        $hash = $this->_utilsService->createRandomHash128();

        $email = $this->_emailService->create(
            $user->email,
            Service\Entity\Email::USER_CHANGE_EMAIL_OLD_CONFIRM,
            array(
                'newEmail' => $newEmail,
            ),
            $hash
        );

        if (!$this->_emailService->send($email, $user->name, $user->surname))
            throw new \Exception('Email not sent');

        $this->_removeOld('User');
        $this->_removeAllEntitiesByEntityIdAndType('User', self::CHANGE_USER_EMAIL_REQUEST, $user->id);

        $passwordChangeConfirm = $this->_createChangeConfirm(
            $user->id,
            $user->email,
            'User',
            self::CHANGE_USER_EMAIL_REQUEST,
            'email',
            $newEmail,
            $hash,
            'Confirmation of user email changing request',
            $this->_dateTimeService->formatMySqlNextHourUtc());

        $this->_saveChangesConfirms(array($passwordChangeConfirm));
    }

    /**
     * @param string $hash
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \Exception
     */
    public function createAfterConfirmUserEmailRequestChangeUserEmail($hash) {
        $this->_removeOld('User');

        $userPasswordChangesConfirms = $this->_findByEntityNameAndFilter('User',
            array(
                'type'               => self::CHANGE_USER_EMAIL_REQUEST,
                'hash'               => $hash,
                'dateTimeExpires >=' => $this->_dateTimeService->formatMySqlUtc() . ' UTC'
            )
        );

        if (empty($userPasswordChangesConfirms))
            throw new \LogicException('Confirmation user email changing request already expired or removed');

        $userPasswordChangeConfirm = $userPasswordChangesConfirms[0];

        $user = $this->_userService->getById($userPasswordChangeConfirm->entityId);
        if ($user === null)
            throw new \InvalidArgumentException('No user found for this action');

        $this->_removeAllEntitiesByEntityIdAndType('User', self::CHANGE_USER_EMAIL_REQUEST, $user->id);

        $email = $this->_contextService->executeInUserContext(function() use ($userPasswordChangeConfirm, $hash){
            return $this->_emailService->create(
                $userPasswordChangeConfirm->newValue,
                Service\Entity\Email::USER_CHANGE_EMAIL_NEW_CONFIRM,
                array(
                    'oldEmail' => $userPasswordChangeConfirm->value,
                ),
                $hash
            );
        }, $user);

        if (empty($email))
            throw new \LogicException('Email not created');

        if (!$this->_emailService->send($email, $user->name, $user->surname))
            throw new \Exception('Email not sent');

        $passwordChangeConfirm = $this->_createChangeConfirm(
            $user->id,
            $userPasswordChangeConfirm->value,
            'User',
            self::CHANGE_USER_EMAIL,
            'email',
            $userPasswordChangeConfirm->newValue,
            $hash,
            'Confirmation of user email changing',
            $this->_dateTimeService->formatMySqlNextHourUtc());

        $this->_saveChangesConfirms(array($passwordChangeConfirm));
    }

    /**
     * @param string $hash
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function changeAfterConfirmUserEmail($hash) {
        $this->_removeOld('User');

        $userPasswordChangesConfirms = $this->_findByEntityNameAndFilter('User',
            array(
                'type'               => self::CHANGE_USER_EMAIL,
                'hash'               => $hash,
                'dateTimeExpires >=' => $this->_dateTimeService->formatMySqlUtc() . ' UTC'
            )
        );

        if (empty($userPasswordChangesConfirms))
            throw new \LogicException('Confirmation user email changing already expired or removed');

        $userPasswordChangeConfirm = $userPasswordChangesConfirms[0];

        $user = $this->_userService->getById($userPasswordChangeConfirm->entityId);
        if ($user === null)
            throw new \InvalidArgumentException('No user found for this action');

        $user->email = $userPasswordChangeConfirm->newValue;

        $this->_userService->save($user);

        $this->_removeAllEntitiesByEntityIdAndType('User', self::CHANGE_USER_EMAIL, $user->id);
    }

    /**
     * @param string $type
     * @param string $hash
     * @return bool
     */
    public function cancelUserChangeConfirm($type, $hash) {
        return $this->_cancelChangeConfirm('User', $type, $hash);
    }

    /**
     * @param string $entityName
     * @param string $type
     * @param string $hash
     * @return bool
     * @throws \LogicException
     */
    private function _cancelChangeConfirm($entityName, $type, $hash) {
        $resultOfDeletion = $this->_removeByEntityNameAndFilter(
            $entityName,
            array(
                'type' => $type,
                'hash' => $hash
            )
        );

        if (!$resultOfDeletion)
            throw new \LogicException('No such confirmation was found');

        return $resultOfDeletion;
    }

    /**
     * @param $email
     * @return Ent\ChangeConfirm[]
     */
    public function getEmailOfUserCreation($email) {

        return $this->_findActualChangesConfirmsByTypeAndNewValue(
            'User',
            array(
                self::CREATE_USER,
                self::CHANGE_USER_EMAIL_REQUEST,
                self::CHANGE_USER_EMAIL
            ),
            $email
        );
    }

    /**
     * @param $phone
     * @return Ent\ChangeConfirm[]
     */
    public function getPhoneOfUserCreation($phone) {
        return $this->_findActualChangesConfirmsByTypeAndNewValue(
            'User',
            array(
                self::CREATE_USER
            ),
            $phone
        );
    }

    /**
     * @param $login
     * @return Ent\ChangeConfirm[]
     */
    public function getLoginOfUserCreation($login) {
        return $this->_findActualChangesConfirmsByTypeAndNewValue(
            'User',
            array(
                self::CREATE_USER
            ),
            $login
        );
    }

    /**
     * @param string $hash
     * @return int
     */
    public function getImageFileIdOfUserCreation($hash) {
        $userCreationImageFileChangesConfirms = $this->_findByEntityNameAndFilter('User',
            array(
                'type'               => self::CREATE_USER,
                'hash'               => $hash,
                'field'              => 'image_file_id',
                'dateTimeExpires >=' => $this->_dateTimeService->formatMySqlUtc() . ' UTC'
            )
        );

        if (count($userCreationImageFileChangesConfirms) === 0)
            return null;

        return $userCreationImageFileChangesConfirms[0]->newValue;
    }


    /**
     * @param string $entityType
     * @param string $entityId
     * @param string $changeConfirmType
     * @throws \RuntimeException
     */
    private function _checkExitingActualChangesConfirms($entityType, $entityId, $changeConfirmType) {
        $changesConfirmsThisUserDelete = $this->_findByEntityNameAndFilter($entityType,
            array(
                'type'               => $changeConfirmType,
                'entityId'           => $entityId,
                'dateTimeExpires >=' => $this->_dateTimeService->formatMySqlUtc() . ' UTC'
            )
        );

        if (count($changesConfirmsThisUserDelete) > 0)
            throw new \LogicException('You have already request this action, check your email');
    }

    /**
     * @param string $entityName
     * @param array $filter
     * @return bool
     */
    private function _removeByEntityNameAndFilter($entityName, $filter) {
        $changesConfirms = $this->_findByEntityNameAndFilter($entityName, $filter);
        if (count($changesConfirms) === 0)
            return false;
        $this->_meetingService->removeChangesConfirms($changesConfirms);
        return true;
    }

    /**
     * @param string $entityName
     * @param array $types
     * @param string $newValue
     * @return Ent\ChangeConfirm[]
     */
    private function _findActualChangesConfirmsByTypeAndNewValue($entityName, array $types, $newValue) {
        return $this->_meetingService->getActualChangesConfirmsByTypeAndNewValue(
            $entityName,
            $types,
            $newValue
        );
    }

    /**
     * @param string $entityName
     * @param array $filter
     * @return Ent\ChangeConfirm[]
     */
    private function _findByEntityNameAndFilter($entityName, $filter) {
        return $this->_styleProperties(
            $this->_meetingService->getChangeConfirmByEntityNameAndFilter($entityName, $filter)
        );
    }

    /**
     * @param Ent\ChangeConfirm[] $changesConfirms
     */
    private function _saveChangesConfirms($changesConfirms) {
        $this->saves($this->_realProperties($changesConfirms));
    }

    /**
     * @param string $entityName
     * @param string $type
     * @param string $field
     * @param string $newValue
     * @param string $hash
     * @param string $comment
     * @param string $dateTimeExpires
     * @return Ent\ChangeConfirm
     */
    private function _createCreateConfirm($entityName, $type, $field, $newValue, $hash, $comment, $dateTimeExpires) {
        $changeConfirm = new Ent\ChangeConfirm();
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
     * @return Ent\ChangeConfirm
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
     * @param Ent\ChangeConfirm[] $userChangesConfirms
     * @return Ent\User
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

        if (empty($userFields))
            throw new \InvalidArgumentException('No user fields to create user');

        return Ent\Factory\Factory::createEntity($userFields, 'Entity\\User', true);
    }

    /**
     * @param $entityName
     */
    private function _removeOld($entityName) {
        $dateTimeNow = $this->_dateTimeService->formatMySqlUtc() . ' UTC';
        $this->_removeByEntityNameAndFilter(
            $entityName,
            array(
                'dateTimeExpires <=' => $dateTimeNow
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

    private function _removeAllEntitiesByEntityIdAndType($entityName, $type, $entityId) {

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
     * @param Ent\User $newUser
     * @param string $hash
     * @return Ent\ChangeConfirm[]
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

    private function _styleProperties($changesConfirms) {
        array_map(function($changeConfirm) {
            $changeConfirm->entityName = $this->_meetingService->styledProperty($changeConfirm->entityName);
            return $changeConfirm;
        }, $changesConfirms);
        return $changesConfirms;
    }

    private function _realProperties($changesConfirms) {
        array_map(function($changeConfirm) {
            $changeConfirm->entityName = $this->_meetingService->realProperty($changeConfirm->entityName);
            return $changeConfirm;
        }, $changesConfirms);
        return $changesConfirms;
    }
}