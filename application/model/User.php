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

    /*
     * Функция записывает в БД новую задачу
     */
    public function createUser($post)
    {
        // Инициализируем значения пользователя и задачи (для исключения ошибок в IDE)
        $idUser = '';
        $task = '';

        // Извлекаем данные из $_POST
        extract($post);

        // Авторизирован ли пользователь
        /*
         * Нужно раскомментировать, если нужно, чтобы только
         * авторизированные пользователи могли добавлять задачи
         */
        if ($_SESSION['unregistered'] == 1) {
            return;
        }

        // Существует ли id пользователя, которое прислал юзер
        if (array_key_exists($idUser, $this->users)) {

            // Инициализируем подключение к БД здесь так как для
            // "checkInjection" нужно действующее соединение в БД
            $dbProvider = new DbProvider();

            // Проверяем поле задачи на SQL-инъекцию
            if ($task = $dbProvider->checkInjection($task)) {

                // Проверка расширения файла пользователя
                if (Helper::checkExtention($_FILES['image']['name'], array("jpg", "jpeg", "gif", "png"))) {
                    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

                    // Вставляем данные в БД
                    $db_result = $dbProvider->queryThis("
INSERT INTO
\"user\" (name,surname,email,level,login,password,salt,email,is_ready,is_ready_only_for_partnership,comment,sex,phone)
VALUES ('$idUser','" . htmlspecialchars($task) . "','" . $ext . "',0)");

                    // Проверяем результат вставки
                    if (empty($db_result['error'])) {

                        // Получаем id добавленной задачи
                        $id = $dbProvider->getLastAutoinctement();

                        // Если id не null
                        if ($id) {

                            // Начинаем загружать файл картинки
                            $downloader = new
                            Downloader(Downloader::IMG_TYPES, "image",
                                "task_" . $id . "." . $ext);
                            $resultDownload = $downloader->download();

                            // Проверяем результат загрузки и проверки типа
                            // уже загруженного файла по MIME
                            if ($resultDownload['error'] == null) {

                                // Изменяем размер
                                $instImage = new Image($this->imageWidth, $this->imageHeight);
                                $resultResize = $instImage->
                                imageResizeProportional($resultDownload['path']);

                                // Проверяем результат
                                if (empty($resultResize['error'])) {
                                    // Переопределяем количество страниц и строк в таблице задач
                                    $this->initCountOfRowsAndPages();
                                    $this->resultOfAddUser = array(
                                        "error" => null,
                                        "response" => "The task successfully added");
                                } else {
                                    $this->resultOfAddUser = array(
                                        "error" => true,
                                        "response" => $resultResize['content']);
                                }
                            } else {

                                // Удаляем запись из БД
                                $resultDelete = $this->deteteTask($id);
                                $this->resultOfAddUser = array(
                                    "error" => true,
                                    "response" => $resultDownload['content'] . $resultDelete['content']);
                            }
                        } else {
                            $this->resultOfAddUser = array(
                                "error" => true,
                                "response" => "There is no last id!");
                        }
                    } else {
                        $this->resultOfAddUser = array(
                            "error" => true,
                            "response" => $db_result['content']);
                    }
                } else {
                    $this->resultOfAddUser = array(
                        "error" => true,
                        "response" => "Bad file extention! Use jpg, gif, png");
                }
            } else {
                $this->resultOfAddUser = array(
                    "error" => true,
                    "response" => "Bad symbols in task or image link!");
            }
        } else {
            $this->resultOfAddUser = array(
                "error" => true,
                "response" => "User not found!");
        }
    }


    public function passwordUpdate(array $post) {

    }

    public function emailUpdate(array $post) {

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

        $permissionsUserForRead = $this->_permissionService->getPermissionsForUserTypesAndSelf(array('read'));
        if (count($permissionsUserForRead) !== 3)
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
            $permissionsUserForRead
        );

        $permissionsForUsers = $this->_permissionService->getPermissionsForUsers(
            $foundedUsersCollection['users'],
            array('update', 'delete')
        );
        $foundedUsersCollection['permissions']['users']  = $permissionsForUsers;
        $foundedUsersCollection['permissions']['create'] = $this->_permissionService->getPermissionsForUsersTypeCreate();

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
            $this->_validatorService->check(array('login' => $post['newLogin']));
            $this->_validatorService->check(array('login' => $post['login']));
            try {
                $this->_validatorService->check(array('loginNotExists' => $post['newLogin']));
            } catch (\InvalidArgumentException $e) {
                return $post['newLogin'] === $post['login'] ? true : false;
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
            $this->updateUser($post);
        } catch (\InvalidArgumentException $e) {
            $this->_createUser($post);
        }
    }

    /**
     * @param array $post
     * @return mixed
     * @throws \InvalidArgumentException
     */
    protected function _createUser(array $post) {

        if (!$this->_permissionService->getPermissionForUserCreate($post['userTypeId']))
            throw new \InvalidArgumentException('No permission to create user with this type');

        $this->_downloaderService->download($_FILES['image']['name']);

        $user = $this->_userProfileService->getUserByArray($post, $_FILES);
        $this->_changeConfirmService->createChangeUserCreation($user, $files);
        return array('text' => 'Вы успешно создали аккаунт, для подтверждения перейдите на указанный вами email');
    }

    /**
     * @param array $post
     * @return mixed
     * @throws \InvalidArgumentException
     */
    protected function _updateUser(array $post) {
        $user = $this->__getUserCheckPermission($post, 'user', 'update');

        $this->__validateStoreData($post, $user);

        return $this->_userProfileService->saveUser($post);
        // @TODO notify by email
    }

    /**
     * @param array $post
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function _deteteUser($post) {
        $user = $this->__getUserCheckPermission($post, 'user', 'delete');

        return $this->_userProfileService->deleteUser($user);
        // @TODO notify by email
    }

    protected function _passwordUpdate(array $post) {
        $user = $this->__getUserCheckPermission($post, 'password', 'update');

        // @TODO create userModifiedField
        // @TODO send to email letter with change password instructions
    }

    protected function _emailUpdate(array $post) {
        $user = $this->__getUserCheckPermission($post, 'email', 'update');

        $this->_validatorService->check(
            array(
                'email'          => $post['email'],
                'emailNotExists' => $post['email'],
            )
        );

        // @TODO create userModifiedField
        // @TODO send to email letter with change email instructions
    }

    protected function _userTypeUpdate(array $post) {
        $user = $this->__getUserCheckPermission($post, 'userTypeId');



        // @TODO create userModifiedField
        // @TODO send to email letter with change password instructions
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
            if (!$this->_permissionService->getPermissionForUser($permission, $user))
                throw new \InvalidArgumentException("No permission to {$permission} {$field}");

        return $user;
    }
    /**
     * @param array $data
     * @param Entity\User|null $user
     */
    protected function __validateStoreData(array $data, $user = null) {
        if (!is_array($data))
            throw new \InvalidArgumentException('Data must be an array');

        $isNeedCheckLogin = true;
        $isUpdate = $user !== null;
        if ($isUpdate)
            if ($data['login'] === $user->login)
                $isNeedCheckLogin = false;

        if ($isNeedCheckLogin)
            $this->_validatorService->check(
                array(
                    'login'          => $data['login'],
                    'loginNotExists' => $data['login']
                )
            );

        if (!$isUpdate)
            $this->_validatorService->check(
                array(
                    'email'          => $data['email'],
                    'emailNotExists' => $data['email'],
                    'userTypeId'     => $data['userTypeId'],
                )
            );

        $this->__validateUserCommonData($data, $user);
    }

    /**
     * @param array $data
     */
    protected function __validateUserCommonData(array $data) {

        $this->_validatorService->check(
            array(
                'userName'    => $data['name'],
                'userSurname' => $data['surname'],
                'bool'        => array(
                    $data['isReady'],
                    $data['isReadyOnlyForPartnership'],
                    $data['sex'],
                ),
                'comment'     => $data['comment'],
                'ext'         => $data['ext'],
                'phone'       => $data['phone'],
            )
        );
    }
}