<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 30.10.2018
 * Time: 10:17
 */

namespace model;

use core\Service\ServiceLocator;
use Service;
use Entity;
use Symfony\Component\Process\Exception\LogicException;

class User extends Model
{
    /**
     * @var Service\Context
     */
    private $_contextService;

    /**
     * @var Service\Repository\Meeting
     */
    private $_meetingService;

    /**
     * @var Service\Repository\InformationSchema
     */
    private $_informationSchemaService;

    /**
     * @var Service\Validator
     */
    private $_validatorService;

    /**
     * @var Service\User\Permission
     */
    private $_permissionService;

    /**
     * @var Service\User\Profile
     */
    private $_userProfileService;

    /**
     * @var Service\User\Type
     */
    private $_userTypeService;

    /**
     * @var Service\Auth
     */
    private $_authService;

    /**
     * @var Service\Email
     */
    private $_emailService;

    /**
     * @var Service\User\ChangeConfirm
     */
    private $_changeConfirmService;

    /**
     * @var Service\Downloader
     */
    private $_downloaderService;

    /**
     * @var Service\Path
     */
    private $_pathService;

    function __construct() {
        parent::__construct();
    }

    protected function _initAjaxServices() {
        $this->_contextService           = ServiceLocator::contextService();
        $this->_meetingService           = ServiceLocator::repositoryMeetingService();
        $this->_informationSchemaService = ServiceLocator::repositoryInformationSchemaService();
        $this->_validatorService         = ServiceLocator::validatorService();
        $this->_permissionService        = ServiceLocator::permissionService();
        $this->_userProfileService       = ServiceLocator::userProfileService();
        $this->_userTypeService          = ServiceLocator::userTypeService();
        $this->_authService              = ServiceLocator::authService();
        $this->_emailService             = ServiceLocator::emailService();
        $this->_changeConfirmService     = ServiceLocator::changeConfirmService();
        $this->_downloaderService        = ServiceLocator::downloaderService();
        $this->_pathService              = ServiceLocator::pathService();
    }

    protected function _initRenderServices() {
        $this->_contextService = ServiceLocator::contextService();
    }

    protected function _initRenderData() {
        $this->_result = array(
            'page'        => 'User',
            'title'       => 'Братья и сестры собрания',
            'description' => 'Собрание. Все братья и сестры участвующие в школе',
            'keywords'    => 'Собрание, братья, сестры'
        );

        $user = $this->_contextService->getUser();
        $this->_frontendConstants = array(
            'PERMISSION_USER_SHOW_SEARCH_BLOCK' => ($user !== null && $user->userTypeId > 1) ? 1 : 0,
        );
    }

    /**
     * @param array $post
     * @return array
     * @throws \Exception
     */
    protected function _getSortingFields(array $post) {

        if (!$this->_contextService->getUser()) {
            throw new \Exception('No permission to unauthenticated user');
        }

        /** @var Entity\InformationSchema\Columns[] */
        $userColumns = $this->_informationSchemaService->getMeetingUserColumns();
        if (!is_array($userColumns)) {
            throw new \Exception('No user columns');
        }

        $renderedUserColumns = array();
        foreach ($userColumns as $userColumn) {
            $columns = array();
            $columns[$userColumn->columnName] = $userColumn->columnComment;
            array_push($renderedUserColumns, $columns);
        }

        $this->_userProfileService->filterSecureUserColumns($renderedUserColumns);

        return $renderedUserColumns;
    }

    /**
     * @param array $post
     * @return array
     * @throws \InvalidArgumentException
     */
    protected function _getUsersBySearch(array $post) {

        $userPermissionsForUserRead = $this->_permissionService->getUserPermissions('read');
        if (count($userPermissionsForUserRead) !== 3)
            throw new \InvalidArgumentException('Not all permissions has been calculated');

        $this->_validatorService->check(
            array(
                'sortingDirection' => $post['sortingDirection'],
                'intPositive'      => $post['pageNumber'],
                'intPositive'      => $post['usersCountOnPage']
            )
        );

        $foundedUsersCollection = $this->_userProfileService->getUsersBySearch(
            $post['search'],
            $post['sortingField'],
            $post['sortingDirection'],
            $post['pageNumber'],
            $post['usersCountOnPage'],
            $userPermissionsForUserRead
        );

        $permissionsForUsers = $this->_permissionService->getUserPermissionsForUsers(
            $foundedUsersCollection['users'],
            array('update', 'delete')
        );
        $foundedUsersCollection['permissions']['users']  = $permissionsForUsers;
        $foundedUsersCollection['permissions']['create'] = $this->_permissionService->getUserPermissionsCreateForUsersTypes();

        $this->_userProfileService->filterSecureUsersFields($foundedUsersCollection['users']);

        return $foundedUsersCollection;
    }

    /**
     * @param array $post
     * @return bool
     * @throws \InvalidArgumentException
     */
    protected function _getIsLoginPossible(array $post) {
        try {
            $this->_validatorService->check(array('login' => $post['login']));

            if ($post['oldLogin'] !== '')
                $this->_validatorService->check(array('login' => $post['oldLogin']));

            try {
                $this->_validatorService->check(
                    array(
                        'loginNotExists'         => $post['login'],
                        'loginUserCreateConfirm' => $post['login'],
                    )
                );
            } catch (\InvalidArgumentException $e) {
                return $post['login'] === $post['oldLogin'] ? true : false;
            }
        } catch (\Throwable $t) {
            return false;
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @param array $post
     * @return mixed
     * @throws \InvalidArgumentException
     */
    protected function _storeUser(array $post) {
        try {
            $this->_validatorService->check(array('intPositive' => $post['id']));
        } catch (\InvalidArgumentException $e) {
            return $this->_createUser($post);
        }
        return $this->_updateUser($post);
    }

    /**
     * @param array $post
     * @return mixed
     * @throws \InvalidArgumentException
     */
    protected function _createUser(array $post) {

        if (!$this->_permissionService->getUserPermissionCreate($post['userTypeId']))
            throw new \InvalidArgumentException('No permission to create user with this type');

        $createValidators = $this->__getCreateValidators(
            $this->__getCommonStoreValidators($post),
            $post
        );

        $this->_validatorService->check($createValidators);

        $this->__handleStoreUserImage($post);

        $user = $this->_setUserByArray($post);
        $this->_changeConfirmService->createChangeUserCreation($user);
        return array('text' => 'Вы успешно создали аккаунт, для подтверждения перейдите на указанный вами email');
    }

    /**
     * @param array $post
     * @return mixed
     * @throws \InvalidArgumentException
     */
    protected function _updateUser(array $post) {
        $user = $this->__getUserCheckPermission($post, 'user', 'update');

        $updateValidators = $this->__getUpdateValidators(
            $this->__getCommonStoreValidators($post),
            $post,
            $user
        );

        $this->_validatorService->check($updateValidators);

        $this->__handleStoreUserImage($post, function($imageExt) use ($user) {
            $tempUserImagePath = $this->_pathService->getTempUserImageFilePath($user->login, $imageExt);
            $userOldImagePath  = $this->_pathService->getUserImageFilePath($user->image, $user->imageExt);

            $newImageName     = $this->_utilsService->createRandomHash32();
            $userNewImagePath = $this->_pathService->getUserImageFilePath($newImageName, $imageExt);
            $isCopied = false;
            if (file_exists($tempUserImagePath)) {
                if (file_exists($userOldImagePath))
                    unlink($userOldImagePath);

                $isCopied = copy($tempUserImagePath, $userNewImagePath);
                unlink($tempUserImagePath);
            }

            if (!$isCopied)
                throw new \RuntimeException('Could not copy user image file');
            return $newImageName;
        });

        $this->_setUserByArray($post, $user);
        $this->_userProfileService->saveUser($user);
        return array('text' => 'Вы успешно изменили данные аккаунта');
    }

    /**
     * @param array $post
     * @return array
     */
    protected function _deleteUser($post) {
        $user = $this->__getUserCheckPermission($post, 'user', 'delete');

        $this->_changeConfirmService->createChangeUserDelete($user);

        return array('text' => 'Вы успешно запросили удаление аккаунта, на email отправлено письмо для подтверждения');
    }

    /**
     * @param array $post
     * @return array
     * @throws \LogicException
     */
    protected function _updateUserType(array $post) {
        $newUserTypeId = $post['userTypeId'];
        $this->_validatorService->check(array('userTypeId' => $newUserTypeId));

        $user = $this->__getUserCheckPermission($post, 'user type', 'update');
        if ($user->userTypeId === $newUserTypeId)
            throw new \LogicException('User type for this account is the same');

        if (!$this->_permissionService->getUserPermissionCreate($newUserTypeId))
            throw new \LogicException('No permission to create user with this type');

        $this->_changeConfirmService->createChangeUserType($user, $newUserTypeId);

        return array('text' => 'Вы успешно изменили тип аккаунта, на email отправлено письмо для подтверждения');
    }

    /**
     * @param array $post
     * @return array
     */
    protected function _updateUserPassword(array $post) {
        $user = $this->__getUserCheckPermission($post, 'password', 'update');

        $this->_changeConfirmService->createChangeUserPassword($user);

        return array('text' => 'Вы успешно запросили новый пароль, на email отправлено письмо');
    }

    /**
     * @param array $post
     * @return array
     */
    protected function _updateUserEmail(array $post) {
        $user = $this->__getUserCheckPermission($post, 'email', 'update');
        $newEmail = $post['email'];

        $this->_validatorService->check(
            array(
                'email'                  => $newEmail,
                'emailNotExists'         => $newEmail,
                'emailUserCreateConfirm' => $newEmail,
            )
        );

        $this->_changeConfirmService->createChangeUserEmailRequest($user, $newEmail);

        return array('text' => 'Вы успешно запросили изменение email, на текущий email отправлено письмо');
    }

    /**
     * @param array $post
     * @param string $field
     * @param string|null $permission
     * @return Entity\User
     */
    protected function __getUserCheckPermission(array $post, $field, $permission = null) {
        try {
            $this->_validatorService->check(array('intPositive' => $post['id']));
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException("User id was not found to {$permission} {$field}");
        }

        $user = $this->_userProfileService->getUserById($post['id']);
        if ($user === null)
            throw new \InvalidArgumentException("User was not found to {$permission} {$field}");

        if ($permission !== null)
            if (!$this->_permissionService->getUserPermissionForUser($permission, $user))
                throw new \InvalidArgumentException("No permission to {$permission} {$field}");

        return $user;
    }

    /**
     * @param array $postChecked
     * @param Entity\User &$user
     * @return Entity\User &$user|void
     */
    private function _setUserByArray(array $postChecked, &$user = null) {
        $isCreate = $user === null;

        if ($isCreate) {
            $user = new Entity\User();
            $user->email      = $postChecked['email'];
            $user->userTypeId = $postChecked['userTypeId'];
        }

        $user->login    = $user->login === $postChecked['login'] ? $user->login : $postChecked['login'];
        $user->name     = $postChecked['name'];
        $user->surname  = $postChecked['surname'];
        $user->phone    = $user->phone === $postChecked['phone'] ? $user->phone : $postChecked['phone'];
        $user->sex      = intval($postChecked['sex']);
        $user->isReady  = intval($postChecked['isReady']);
        $user->isReadyOnlyForPartnership = intval($postChecked['isReadyOnlyForPartnership']);
        $user->image    = $postChecked['image'] === null ? $user->image : $postChecked['image'];
        $user->imageExt = $postChecked['imageExt'] === null ? $user->imageExt : $postChecked['imageExt'];
        $user->comment  = $postChecked['comment'];

        if ($isCreate)
            return $user;
    }

    /**
     * @param array $post
     * @return array
     */
    private function __getCommonStoreValidators(array $post) {
        return array(
            'strlen'  => array(
                array($post['name'],    array(1, 50)),
                array($post['surname'], array(1, 50)),
                array($post['comment'], array(1, 500)),
            ),
            'zeroone' =>
                array (
                    $post['sex'],
                    $post['isReady'],
                    $post['isReadyOnlyForPartnership']
                ),
        );
    }

    /**
     * @param array $commonValidators
     * @param array $post
     * @param Entity\User|null $user
     * @return array
     */
    private function __getUpdateValidators(array $commonValidators, array $post, $user = null) {
        $updateValidators = $commonValidators;
        if ($user->login !== $post['login'])
            $updateValidators = array_merge(
                $updateValidators,
                array(
                    'login'                  => $post['login'],
                    'loginNotExists'         => $post['login'],
                    'loginUserCreateConfirm' => $post['login'],
                )
            );

        if ($user->phone !== $post['phone'])
            $updateValidators = array_merge(
                $updateValidators,
                array(
                    'phone'                  => $post['phone'],
                    'phoneNotExists'         => $post['phone'],
                    'phoneUserCreateConfirm' => $post['phone'],
                )
            );
        return $updateValidators;
    }

    /**
     * @param array $commonValidators
     * @param array $post
     * @return array
     */
    private function __getCreateValidators(array $commonValidators, array $post) {
        return array_merge(
            $commonValidators,
            array(
                'login'                  => $post['login'],
                'loginNotExists'         => $post['login'],
                'loginUserCreateConfirm' => $post['login'],
                'email'                  => $post['email'],
                'emailNotExists'         => $post['email'],
                'emailUserCreateConfirm' => $post['email'],
                'userTypeId'             => $post['userTypeId'],
                'phone'                  => $post['phone'],
                'phoneNotExists'         => $post['phone'],
                'phoneUserCreateConfirm' => $post['phone'],
            )
        );
    }

    /**
     * @param array $post
     * @param callable|null $updateCallback
     */
    private function __handleStoreUserImage(array &$post, callable $updateCallback = null) {
        if (array_key_exists('image', $_FILES)) {
            try {
                $path = $this->_downloaderService->downloadUserImage('image', $post['login']);

                $this->_validatorService->check(
                    array(
                        'extImage'  => $path,
                        'mimeImage' => $path
                    )
                );

                $imageFileName = null;
                $imageExt = $this->_utilsService->getExtention($path);
                if ($updateCallback !== null && is_callable($updateCallback))
                    $imageFileName = $updateCallback($imageExt);

                $post['image']    = $imageFileName;
                $post['imageExt'] = $imageExt;
            } catch (\InvalidArgumentException $e) {
                unlink($path);
                throw new \InvalidArgumentException($e->getMessage());
            }
        }
    }
}