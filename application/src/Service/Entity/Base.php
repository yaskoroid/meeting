<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 25.12.2018
 * Time: 13:18
 */

namespace Service\Entity;

use core\Service\ServiceLocator;
use Service;

class Base extends Service\Basic {

    /**
     * @var array
     */
    private static $_entities = array(
        'ChangeConfirm' => array(
            'name' => 'Подтверждение изменения',
            'fields' => array(
                'id'              => array('name' => 'Id'),
                'entityName'      => array('name' => 'Имя изменяемой сущности'),
                'entityId'        => array('name' => 'Изменяемая сущность'),
                'type'            => array('name' => 'Тип'),
                'field'           => array('name' => 'Поле'),
                'value'           => array('name' => 'Значение'),
                'newValue'        => array('name' => 'Новое значение'),
                'hash'            => array('name' => 'Хэш'),
                'comment'         => array('name' => 'Комментарий'),
                'dateTimeExpires' => array('name' => 'Дата окончания актуальности'),
            ),
        ),
        'Email' => array(
            'name' => 'E-mail',
            'fields' => array(
                'id'              => array('name' => 'Id'),
                'emailFromUserId' => array('name' => 'Отправитель'),
                'emailToUserId'   => array('name' => 'Получатель'),
                'type'            => array('name' => 'Тип'),
                'title'           => array('name' => 'Заголовок'),
                'body'            => array('name' => 'Тело'),
                'css'             => array('name' => 'Css'),
                'comment'         => array('name' => 'Комментарий'),
                'dateTime'        => array('name' => 'Дата'),
                'isAccepted'      => array('name' => 'Получено'),
            ),
        ),
        'File' => array(
            'name' => 'Файл',
            'fields' => array(
                'id'              => array(
                    'name'     => 'Id',
                    'isPublic' => true,
                ),
                'type'            => array('name' => 'Тип'),
                'mime'            => array('name' => 'Mine тип'),
                'mimeDescription' => array('name' => 'Описание типа'),
                'name'            => array(
                    'name'     => 'Имя',
                    'isPublic' => true,
                ),
                'extension'       => array(
                    'name'     => 'Расширение',
                    'isPublic' => true,
                ),
                'description'     => array(
                    'name'     => 'Описание',
                    'isPublic' => true,
                ),
                'isTemp'          => array('name' => 'Временный'),
            ),
        ),
        'Lesson' => array(
            'name' => 'Урок',
            'fields' => array(
                'id' => array('name' => 'Id'),
                'textbookId' => array('name' => 'Учебник'),
                'number' => array('name' => 'Номер'),
                'name' => array('name' => 'Название'),
                'filesIds' => array('name' => 'Файлы'),
                'isDialog' => array('name' => 'Диалог'),
                'isRead' => array('name' => 'Чтение'),
                'isSpeech' => array('name' => 'Речь'),
            ),
            'isSetting' => true,
        ),
        'Meeting' => array(
            'name' => 'Собрание',
            'fields' => array(
                'id'   => array('name' => 'Id'),
                'date' => array('name' => 'Дата'),
            ),
            'isSetting' => true,
        ),
        'Task' => array(
            'name' => 'Задание',
            'fields' => array(
                'id'                => array('name' => 'Id'),
                'meetingId'         => array('name' => 'Собрание'),
                'number'            => array('name' => 'Номер'),
                'taskTypeId'        => array('name' => 'Тип'),
                'lessonId'          => array('name' => 'Урок'),
                'responsibleUserId' => array('name' => 'Ответственный'),
                'partnersUsersIds'  => array('name' => 'Партнеры'),
                'isDone'            => array('name' => 'Выполнено'),
                'isHall'            => array('name' => 'В зале'),
                'comment'           => array('name' => 'Комментарий'),
            ),
        ),
        'TaskDateTypeSourceComment' => array(
            'name' => 'Комментарий даты, типа и источника задания',
            'fields' => array(
                'id'               => array('name' => 'Id'),
                'taskTargetDateId' => array('name' => 'Целевая дата'),
                'taskTypeId'       => array('name' => 'Тип задания'),
                'taskSourceId'     => array('name' => 'Источник задания'),
                'comment'          => array('name' => 'Комментарий'),
            ),
            'isSetting' => true,

        ),
        'TaskSource' => array(
            'name' => 'Источник задания',
            'fields' => array(
                'id'        => array('name' => 'Id'),
                'name'      => array('name' => 'Название'),
                'shortName' => array('name' => 'Короткое имя'),
            ),
            'isSetting' => true,

        ),
        'TaskTargetDate' => array(
            'name' => 'Целевая дата задания',
            'fields' => array(
                'id'        => array('name' => 'Id'),
                'startDate' => array('name' => 'Начальная дата'),
                'endDate'   => array('name' => 'Конечная дата'),
            ),
            'isSetting' => true,

        ),
        'TaskType' => array(
            'name' => 'Тип задания',
            'fields' => array(
                'id'   => array('name' => 'Id'),
                'name' => array('name' => 'Название'),
            ),
            'isSetting' => true,

        ),
        'Textbook' => array(
            'name' => 'Учебник',
            'fields' => array(
                'id'     => array('name' => 'Id'),
                'number' => array('name' => 'Номер'),
                'name'   => array('name' => 'Название'),
            ),
            'isSetting' => true,
        ),
        'User' => array(
            'name' => 'Пользователь',
            'fields' => array(
                'id' => array(
                    'name'     => 'Id',
                    'isPublic' => true,
                ),
                'login' => array(
                    'name'     => 'Логин',
                    'isPublic' => true,
                ),
                'email' => array(
                    'name'     => 'E-mail',
                    'isPublic' => true,
                ),
                'userTypeId' => array(
                    'name'     => 'Тип',
                    'isPublic' => true,
                ),
                'imageFileId' => array(
                    'name'         => 'Файл картинки',
                    'isPublic'     => true,
                    'isNotShowing' => true,
                ),
                'name' => array(
                    'name'     => 'Имя',
                    'isPublic' => true,
                ),
                'surname' => array(
                    'name'     => 'Фамилия',
                    'isPublic' => true,
                ),
                'phone' => array(
                    'name'     => 'Телефон',
                    'isPublic' => true,
                ),
                'sex' => array(
                    'name'     => 'Пол',
                    'isPublic' => true,
                ),
                'isReady' => array(
                    'name'     => 'Готов',
                    'isPublic' => true,
                ),
                'isReadyOnlyForPartnership' => array(
                    'name'     => 'Только партнерство',
                    'isPublic' => true,
                ),
                'comment' => array(
                    'name'     => 'Комментарий',
                    'isPublic' => true,
                ),
                'salt'                      => array('name' => 'Соль'),
                'password'                  => array('name' => 'Хэш пароля'),
                'customizableSessionValues' => array('name' => 'Значения пользовательской сессии'),
                'sessionId'                 => array('name' => 'Id сессии'),
            ),
        ),
        'UserType' => array(
            'name' => 'Тип пользователя',
            'fields' => array(
                'id'          => array(
                    'name' => 'Id',
                    'isPublic' => true,
                ),
                'role'        => array(
                    'name' => 'Роль',
                    'isPublic' => true,
                ),
                'description' => array(
                    'name' => 'Описание',
                    'isPublic' => true,
                ),

                'permissionCreateSelfUser' => array('name' => 'Разрешение создавать себя'),
                'permissionUpdateSelfUser' => array('name' => 'Разрешение обновлять себя'),
                'permissionDeleteSelfUser' => array('name' => 'Разрешение удалять себя'),
                'permissionReadSelfUser'   => array('name' => 'Разрешение читать себя'),

                'permissionCreateCustomer' => array('name' => 'Разрешение создавать пользователя'),
                'permissionUpdateCustomer' => array('name' => 'Разрешение обновлять пользователя'),
                'permissionDeleteCustomer' => array('name' => 'Разрешение удалять пользователя'),
                'permissionReadCustomer'   => array('name' => 'Разрешение читать пользователя'),

                'permissionCreateAdministrator' => array('name' => 'Разрешение создавать администратора'),
                'permissionUpdateAdministrator' => array('name' => 'Разрешение создавать администратора'),
                'permissionDeleteAdministrator' => array('name' => 'Разрешение удалять администратора'),
                'permissionReadAdministrator'   => array('name' => 'Разрешение читать администратора'),

                'permissionCreateMeeting' => array('name' => 'Разрешение создавать собрание'),
                'permissionUpdateMeeting' => array('name' => 'Разрешение обновлять собрание'),
                'permissionDeleteMeeting' => array('name' => 'Разрешение удалять собрание'),
                'permissionReadMeeting'   => array('name' => 'Разрешение читать собрание'),

                'permissionCreateSelfTask' => array('name' => 'Разрешение создавать себе задания'),
                'permissionUpdateSelfTask' => array('name' => 'Разрешение обновлять себе задания'),
                'permissionDeleteSelfTask' => array('name' => 'Разрешение удалять себе задания'),
                'permissionReadSelfTask'   => array('name' => 'Разрешение читать себе задания'),

                'permissionCreateTask' => array('name' => 'Разрешение создавать задания'),
                'permissionUpdateTask' => array('name' => 'Разрешение обновлять задания'),
                'permissionDeleteTask' => array('name' => 'Разрешение удалять задания'),
                'permissionReadTask'   => array('name' => 'Разрешение читать задания'),

                'permissionCreateTaskSource' => array('name' => 'Разрешение создавать источник задания'),
                'permissionUpdateTaskSource' => array('name' => 'Разрешение обновлять источник задания'),
                'permissionDeleteTaskSource' => array('name' => 'Разрешение удалять источник задания'),
                'permissionReadTaskSource'   => array('name' => 'Разрешение читать источник задания'),

                'permissionCreateTaskTargetDate' => array('name' => 'Разрешение создавать целевую дату задания'),
                'permissionUpdateTaskTargetDate' => array('name' => 'Разрешение обновлять целевую дату задания'),
                'permissionDeleteTaskTargetDate' => array('name' => 'Разрешение удалять целевую дату задания'),
                'permissionReadTaskTargetDate'   => array('name' => 'Разрешение читать целевую дату задания'),

                'permissionCreateTaskType' => array('name' => 'Разрешение создавать тип задания'),
                'permissionUpdateTaskType' => array('name' => 'Разрешение обновлять тип задания'),
                'permissionDeleteTaskType' => array('name' => 'Разрешение удалять тип задания'),
                'permissionReadTaskType'   => array('name' => 'Разрешение читать тип задания'),

                'permissionCreateTaskTypeDateComment' => array(
                    'name' => 'Разрешение создавать комментарий целевой даты типа задания'
                ),
                'permissionUpdateTaskTypeDateComment' => array(
                    'name' => 'Разрешение обновлять комментарий целевой даты типа задания'
                ),
                'permissionDeleteTaskTypeDateComment' => array(
                    'name' => 'Разрешение удалять комментарий целевой даты типа задания'
                ),
                'permissionReadTaskTypeDateComment'   => array(
                    'name' => 'Разрешение читать комментарий целевой даты типа задания'
                ),

                'permissionCreateTextbook' => array('name' => 'Разрешение создавать учебник'),
                'permissionUpdateTextbook' => array('name' => 'Разрешение обновлять учебник'),
                'permissionDeleteTextbook' => array('name' => 'Разрешение удалять учебник'),
                'permissionReadTextbook'   => array('name' => 'Разрешение читать учебник'),

                'permissionCreateLesson' => array('name' => 'Разрешение создавать урок'),
                'permissionUpdateLesson' => array('name' => 'Разрешение обновлять урок'),
                'permissionDeleteLesson' => array('name' => 'Разрешение удалять урок'),
                'permissionReadLesson'   => array('name' => 'Разрешение читать урок'),
            ),
        ),
    );

    /**
     * @var Service\Utils
     */
    protected $_utilsService;

    /**
     * @var Service\Repository\Meeting
     */
    protected $_meetingService;

    function __construct() {
        self::_initServices();
    }

    private function _initServices() {
        $this->_utilsService   = ServiceLocator::utilsService();
        $this->_meetingService = ServiceLocator::repositoryMeetingService();
    }

    /**
     * @param string $methodName
     * @param string $arguments
     * @return mixed
     */
    function __call($methodName, $arguments) {
        if (!method_exists($this, '_' . $methodName))
            throw new \RuntimeException('Entities method does not exists');

        if (count($arguments) === 0)
            throw new \InvalidArgumentException('Arguments count is zero');

        try {
            $this->checkEntity($arguments[0]);
        } catch (\InvalidArgumentException $e) {
            $fullClassName = get_class($this);
            if (empty($fullClassName))
                throw new \InvalidArgumentException($e->getMessage());

            if (!is_subclass_of($fullClassName, self::class))
                throw new \InvalidArgumentException($e->getMessage());

            $entityName = $this->_utilsService->trimClassNamespace($fullClassName);
            array_unshift($arguments, $entityName);
        }

        return call_user_func_array(array($this, '_' . $methodName), $arguments);
    }

    /**
     * @return array
     * @throws \RuntimeException
     */
    public function getEntities() {
        return array_keys(self::$_entities);
    }

    /**
     * @param string $entityName
     * @return array
     * @throws \RuntimeException
     */
    public function getEntityProperties($entityName) {
        $this->checkEntity($entityName);

        $entityProperties = $this->_utilsService->arrayGetRecursive(self::$_entities, array($entityName));

        if ($entityProperties === null || !is_array($entityProperties) || count($entityProperties) === 0)
            throw new \RuntimeException('Entity properties names must be not empty array');

        return $entityProperties;
    }

    /**
     * @param string $entityName
     * @return string
     * @throws \RuntimeException
     */
    public function getEntityName($entityName) {
        $entityProperties   = $this->getEntityProperties($entityName);
        $entityNameProperty = $this->_utilsService->arrayGetRecursive($entityProperties, array('name'));

        if ($entityNameProperty === null || !is_string($entityNameProperty))
            throw new \RuntimeException('Entity name must be not empty string');

        return $entityNameProperty;
    }

    /**
     * @param string $entityName
     * @return array
     * @throws \RuntimeException
     */
    public function getEntityFieldsProperties($entityName) {
        $entityProperties       = $this->getEntityProperties($entityName);
        $entityFieldsProperties = $this->_utilsService->arrayGetRecursive($entityProperties, array('fields'));

        if ($entityFieldsProperties === null ||
            !is_array($entityFieldsProperties) ||
            count($entityFieldsProperties) === 0
        )
            throw new \RuntimeException('Entity field property must be not empty array');

        return $entityFieldsProperties;
    }

    /**
     * @param string $entityName
     * @return array
     * @throws \RuntimeException
     */
    public function getEntityFields($entityName) {
        $entityFieldsProperties = $this->getEntityFieldsProperties($entityName);

        return array_keys($entityFieldsProperties);
    }

    /**
     * @param string $entityName
     * @return array
     * @throws \RuntimeException
     */
    public function getEntityFieldsNames($entityName) {
        $entityFieldsProperties = $this->getEntityFieldsProperties($entityName);
        $entityFieldsNames      = $this->_utilsService->extractField('name', $entityFieldsProperties, true);

        if ($entityFieldsNames === null || !is_array($entityFieldsNames) || count($entityFieldsNames) === 0)
            throw new \RuntimeException('Entity fields names must be not empty array');

        return $entityFieldsNames;
    }

    /**
     * @param string $entityName
     * @return array
     * @throws \RuntimeException
     */
    public function getEntityPublicFields($entityName) {
        $entityFieldsProperties = $this->getEntityFieldsProperties($entityName);
        $entityFieldsPublic     = $this->_utilsService->extractField('isPublic', $entityFieldsProperties, true);

        if ($entityFieldsPublic === null || !is_array($entityFieldsPublic))
            throw new \RuntimeException('Entity fields public must an array');

        foreach ($entityFieldsPublic as $key => $item) {
            if (empty($item))
                unset($entityFieldsPublic[$key]);
        }
        return array_keys($entityFieldsPublic);
    }

    /**
     * @param string $entityName
     * @return array
     * @throws \RuntimeException
     */
    public function getEntityNotShowingFields($entityName) {
        $entityFieldsProperties = $this->getEntityFieldsProperties($entityName);
        $entityFieldsNotShowing = $this->_utilsService->extractField('isNotShowing', $entityFieldsProperties, true);

        if ($entityFieldsNotShowing === null || !is_array($entityFieldsNotShowing))
            throw new \RuntimeException('Entity fields not showing must an array');

        foreach ($entityFieldsNotShowing as $key => $item) {
            if (empty($item))
                unset($entityFieldsNotShowing[$key]);
        }
        return array_keys($entityFieldsNotShowing);
    }

    /**
     * @return array
     * @throws \RuntimeException
     */
    public function getEntitiesSettingsNames() {
        $entitiesSettingsNames = $this->_utilsService->extractField('isSetting', self::$_entities, true);

        if ($entitiesSettingsNames === null || !is_array($entitiesSettingsNames))
            throw new \RuntimeException('Entities settings names must an array');

        foreach ($entitiesSettingsNames as $key => $item) {
            if (empty($item))
                unset($entitiesSettingsNames[$key]);
        }
        return array_keys($entitiesSettingsNames);
    }

    /**
     * @param $entityName
     * @return array
     */
    public function getEntityFilesFields($entityName) {
        $entityFields = $this->getEntityFields($entityName);
        $result = array();
        foreach ($entityFields as $entityFieldName) {
            if (strpos($entityFieldName, 'fileId') !== false ||
                strpos($entityFieldName, 'FileId') !== false ||
                strpos($entityFieldName, 'filesIds') !== false ||
                strpos($entityFieldName, 'FilesIds') !== false)
                    array_push($result);
        }
        return $result;
    }

    /**
     * @param string $entityName
     * @param string $field
     * @return string
     */
    public function getFileField($entityName, $field) {
        $filesFields = $this->getEntityFilesFields($entityName);

        if (count($filesFields) === 0)
            throw new \InvalidArgumentException('This entity has no files fields');

        if (count($filesFields) === 1)
            return $filesFields[0];

        if (!in_array($field, $filesFields))
            throw new \InvalidArgumentException('Bad field of entity');

        return $field;
    }

    /**
     * @param string $entityFileFieldName
     * @return bool
     * @throws \InvalidOperatorExtension
     */
    public function isOneFileField($entityFileFieldName) {
        if (strpos($entityFileFieldName, 'fileId') !== false || strpos($entityFileFieldName, 'FileId') !== false)
            return true;
        if (strpos($entityFileFieldName, 'filesIds') !== false || strpos($entityFileFieldName, 'FilesIds') !== false)
            return false;
        throw new \InvalidArgumentException('This is not entity file field name');
    }

    /**
     * @param string $entityName
     * @param array $entities
     */
    public function filterPublicEntitiesFields($entityName, array &$entities) {
        if (!is_array($entities))
            throw new \InvalidArgumentException('Users must be an array');

        foreach ($entities as $entity) {
            $this->filterPublicEntityFields($entityName, $entity);
        }
    }

    /**
     * @param string $entityName
     * @param mixed $entity
     */
    public function filterPublicEntityFields($entityName, &$entity) {
        $this->checkEntity($entityName);

        if ($entity === null)
            return;

        $publicFields = $this->getEntityPublicFields($entityName);
        foreach ($entity as $entityField => $entityValue) {
            if (!in_array($entityField, $publicFields))
                unset($entity->{$entityField});
        }
    }

    /**
     * @param string $entityName
     * @param mixed $entity
     */
    public function filterNotShowingEntityFields($entityName, &$entity) {
        $this->checkEntity($entityName);

        if ($entity === null)
            return;

        $notShowingFields = $this->getEntityNotShowingFields($entityName);
        foreach ($entity as $entityField => $entityValue) {
            if (in_array($entityField, $notShowingFields))
                unset($entity->{$entityField});
        }
    }

    /**
     * @return string
     */
    protected function _getClass() {
        return $this->_utilsService->trimClassNamespace(get_class($this));
    }

    /**
     * @param string $entityName
     * @throws \InvalidArgumentException
     */
    private function checkEntity($entityName) {
        if (!is_string($entityName))
            throw new \InvalidArgumentException('Entity must be string');

        if (!in_array($entityName, $this->getEntities()))
            throw new \InvalidArgumentException('This entity does not exist');
    }

    /**
     * @param string $entityName
     * @return mixed
     */
    private function _get($entityName) {
        return $this->_meetingService->getEntities($entityName);
    }

    /**
     * @param string $entityName
     * @param int $id
     * @return mixed|null
     */
    private function _getById($entityName, $id) {
        try {
            return $this->_meetingService->getEntityById($entityName, $id);
        } catch(\Exception $e) {
            logException($e);
            return null;
        }
    }

    /**
     * @param string $entityName
     * @param array $ids
     * @return array
     */
    private function _getByIds($entityName, array $ids) {
        return $this->_meetingService->getEntitiesByIds($entityName, $ids);
    }

    /**
     * @param string $entityName
     * @param mixed $entity
     */
    private function _save($entityName, $entity) {
        $this->_meetingService->saveEntity($entityName, $entity);
    }

    /**
     * @param string $entityName
     * @param array $entities
     */
    private function _saves($entityName, array $entities) {
        $this->_meetingService->saveEntities($entityName, $entities);
    }

    /**
     * @param string $entityName
     * @param array $ids
     */
    private function _deleteByIds($entityName, array $ids) {
        $this->_meetingService->deleteEntitiesByIds($entityName, $ids);
    }

    /**
     * @param string $entityName
     * @param array $entities
     */
    public function _delete($entityName, array $entities) {
        $this->_meetingService->deleteEntities($entityName, $entities);
    }
}