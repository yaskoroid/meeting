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

class User extends Model {
    /**
     * @var Service\Context
     */
    private $_contextService;

    /**
     * @var Service\Validator
     */
    private $_validatorService;

    /**
     * @var Service\Permission
     */
    private $_permissionService;

    /**
     * @var Service\Entity\User
     */
    private $_userService;

    /**
     * @var Service\Entity\UserType
     */
    private $_userTypeService;

    /**
     * @var Service\Auth
     */
    private $_authService;

    /**
     * @var Service\Entity\Email
     */
    private $_emailService;

    /**
     * @var Service\Entity\ChangeConfirm
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

    /**
     * @var Service\Entity\File
     */
    private $_fileService;

    function __construct() {
        parent::__construct();
    }

    protected function _initAjaxServices() {
        $this->_contextService       = ServiceLocator::contextService();
        $this->_validatorService     = ServiceLocator::validatorService();
        $this->_permissionService    = ServiceLocator::permissionService();
        $this->_userService          = ServiceLocator::userService();
        $this->_userTypeService      = ServiceLocator::userTypeService();
        $this->_authService          = ServiceLocator::authService();
        $this->_emailService         = ServiceLocator::emailService();
        $this->_changeConfirmService = ServiceLocator::changeConfirmService();
        $this->_downloaderService    = ServiceLocator::downloaderService();
        $this->_pathService          = ServiceLocator::pathService();
        $this->_fileService          = ServiceLocator::fileService();
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
        if (!$this->_contextService->getUser())
            throw new \Exception('No permission to unauthenticated user');

        $user = new Entity\User();
        $this->_userService->filterPublicEntityFields('User', $user);
        $this->_userService->filterNotShowingEntityFields('User', $user);

        $filteredUserColumns = $this->_userService->getEntityFieldsNames('User');
        foreach ($filteredUserColumns as $field => $value) {
            if (!$this->_utilsService->arrayKeyExistRecursive($user, array($field)))
                unset($filteredUserColumns[$field]);
        }

        return $filteredUserColumns;
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

        $foundedUsersCollection = $this->_userService->getUsersBySearch(
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

        $this->_userService->filterPublicEntitiesFields('User', $foundedUsersCollection['users']);

        $foundedUsersCollection['files'] = array();
        $imageFileIds = $this->_utilsService->extractField('imageFileId', $foundedUsersCollection['users']);
        foreach ($imageFileIds as $key => $item) {
            if (empty($item))
                unset($imageFileIds[$key]);
        }
        if (count($imageFileIds) === 0)
            return $foundedUsersCollection;

        $imageFiles = $this->_fileService->getByIds($imageFileIds);
        if (count($imageFiles) === 0)
            return $foundedUsersCollection;

        $imageFilesIndex = $this->_utilsService->buildIndex($imageFiles);
        $this->_fileService->filterPublicFilesFields($imageFilesIndex);
        $foundedUsersCollection['files'] = $imageFilesIndex;

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

        $createValidators = $this->_getCreateValidators(
            $this->_getCommonStoreValidators($post),
            $post
        );

        $this->_validatorService->check($createValidators);

        if (!empty($_FILES['image'])) {
            $userImageTempFile = $this->_downloaderService->downloadFileToTemp(
                'image',
                'Загружен новый файл аватарки пользователя',
                'image'
            );
            $post['imageFileId'] = $userImageTempFile->id;
        }

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
        $user = $this->_getUserCheckPermission($post, 'user', 'update');

        $updateValidators = $this->_getUpdateValidators(
            $this->_getCommonStoreValidators($post),
            $post,
            $user
        );

        $this->_validatorService->check($updateValidators);

        if (!empty($_FILES['image'])) {
            $userImageTempFile = $this->_downloaderService->downloadFileToTemp(
                'image',
                'Обновлен файл аватарки пользователя',
                'image'
            );

            $userImageFile = $this->_downloaderService->storeFromTemp($userImageTempFile->id);
            $post['imageFileId'] = $userImageFile->id;

            if ($user->imageFileId !== null)
                $this->_fileService->deleteByIds(array($user->imageFileId));
        }

        $this->_setUserByArray($post, $user);
        $this->_userService->save($user);
        return array('text' => 'Вы успешно изменили данные аккаунта');
    }

    /**
     * @param array $post
     * @return array
     */
    protected function _deleteUser($post) {
        $user = $this->_getUserCheckPermission($post, 'user', 'delete');

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

        $user = $this->_getUserCheckPermission($post, 'user type', 'update');
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
        $user = $this->_getUserCheckPermission($post, 'password', 'update');

        $this->_changeConfirmService->createChangeUserPassword($user);

        return array('text' => 'Вы успешно запросили новый пароль, на email отправлено письмо');
    }

    /**
     * @param array $post
     * @return array
     */
    protected function _updateUserEmail(array $post) {
        $user = $this->_getUserCheckPermission($post, 'email', 'update');
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
    private function _getUserCheckPermission(array $post, $field, $permission = null) {
        try {
            $this->_validatorService->check(array('intPositive' => $post['id']));
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException("User id was not found to {$permission} {$field}");
        }

        $user = $this->_userService->getById($post['id']);
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

        $user->login       = $user->login === $postChecked['login'] ? $user->login : $postChecked['login'];
        $user->name        = $postChecked['name'];
        $user->surname     = $postChecked['surname'];
        $user->phone       = $user->phone === $postChecked['phone'] ? $user->phone : $postChecked['phone'];
        $user->sex         = intval($postChecked['sex']);
        $user->isReady     = intval($postChecked['isReady']);
        $user->isReadyOnlyForPartnership = intval($postChecked['isReadyOnlyForPartnership']);
        $user->imageFileId = $postChecked['imageFileId'] === null ? $user->imageFileId : $postChecked['imageFileId'];
        $user->comment     = $postChecked['comment'];

        if ($isCreate)
            return $user;
    }

    /**
     * @param array $post
     * @return array
     */
    private function _getCommonStoreValidators(array $post) {
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
    private function _getUpdateValidators(array $commonValidators, array $post, $user = null) {
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
    private function _getCreateValidators(array $commonValidators, array $post) {
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
}