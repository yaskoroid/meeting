<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 11.12.2018
 * Time: 15:20
 */

namespace Service;

use core\Service\ServiceLocator;
use Service\Repository\Meeting;
use Service;

class Settings extends Basic {

    /**
     * @var array
     */
    public static $entities = array(
        'meeting',
        'lesson',
        'textbook',
        'taskSource',
        'taskTargetDate',
        'taskType',
        'taskDateTypeSourceComment'
    );

    /**
     * @var array
     */
    public static $entitiesNames = array(
        'meeting'                   => 'Собрание',
        'lesson'                    => 'Урок',
        'textbook'                  => 'Учебник',
        'taskSource'                => 'Источник задания',
        'taskTargetDate'            => 'Целевая дата задания',
        'taskType'                  => 'Тип задания',
        'taskDateTypeSourceComment' => 'Комментарий даты, типа и источника задания'
    );

    /**
     * @var array
     */
    public static $entitiesFieldsNames = array(
        'meeting' => array(
            'id'   => 'Id',
            'date' => 'Дата',
        ),
        'lesson' => array(
            'id'         => 'Id',
            'textbookId' => 'Учебник',
            'number'     => 'Номер',
            'name'       => 'Название',
            'isDialog'   => 'Диалог',
            'isRead'     => 'Чтение',
            'isSpeach'   => 'Речь'
        ),
        'textbook' => array(
            'id'     => 'Id',
            'number' => 'Номер',
            'name'   => 'Название',
        ),
        'taskSource' => array(
            'id'        => 'Id',
            'name'      => 'Название',
            'shortName' => 'Короткое имя',
        ),
        'taskTargetDate' => array(
            'id'        => 'Id',
            'startDate' => 'Начальная дата',
            'endDate'   => 'Конечная дата',
        ),
        'taskType' => array(
            'id'   => 'Id',
            'name' => 'Название',
        ),
        'taskDateTypeSourceComment' => array(
            'id'               => 'Id',
            'taskTargetDateId' => 'Целевая дата',
            'taskTypeId'       => 'Тип задания',
            'taskSourceId'     => 'Источник задания',
            'comment'          => 'Комментарий',
        ),
    );

    /**
     * @var Service\Utils
     */
    private $_utilsService;

    /**
     * @var Meeting
     */
    private $_meetingService;

    function __construct() {
        self::_initServices();
    }

    private function _initServices() {
        $this->_utilsService   = ServiceLocator::utilsService();
        $this->_meetingService = ServiceLocator::repositoryMeetingService();
    }

    function __call($methodName, $arguments) {
        if (!method_exists($this, $methodName))
            throw new \RuntimeException("Setting's method does not exists");

        if (count($arguments) === 0)
            throw new \InvalidArgumentException('Arguments count is zero');

        if (!in_array($arguments[0], self::$entities))
            throw new \InvalidArgumentException('This setting does not exist');

        return call_user_func_array(array($this, $methodName), $arguments);
    }

    /**
     * @param string $setting
     * @return mixed
     */
    private function get($setting) {
        return $this->_meetingService->getSettings($setting);
    }

    /**
     * @param string $setting
     * @param int $id
     * @return mixed|null
     */
    private function getById($setting, $id) {
        try {
            return $this->_meetingService->getSettingById($setting, $id);
        } catch(\Exception $e) {
            logException($e);
            return null;
        }
    }

    /**
     * @param string $setting
     * @param mixed $entity
     */
    private function save($setting, $entity) {
        $this->_meetingService->saveSetting($setting, $entity);
    }

    /**
     * @param string $setting
     * @param array $ids
     */
    private function delete($setting, array $ids) {
        $this->_meetingService->deleteSettingsByIds($setting, $ids);
    }
}