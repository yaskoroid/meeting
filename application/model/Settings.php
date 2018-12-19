<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 27.11.2018
 * Time: 11:49
 */

namespace model;

use core\Service\ServiceLocator;
use Service;
use Entity;

class Settings extends Model {

    /**
     * @var Service\User\Permission
     */
    private $_permissionService;

    /**
     * @var Service\Validator
     */
    private $_validatorService;

    /**
     * @var Service\Settings
     */
    private $_settingsService;

    function __construct() {
        parent::__construct();
    }

    protected function _initAjaxServices() {
        $this->_permissionService = ServiceLocator::permissionService();
        $this->_validatorService  = ServiceLocator::validatorService();
        $this->_settingsService   = ServiceLocator::settingsService();
    }

    protected function _initRenderServices() {}

    protected function _initRenderData() {
        $this->_result = array(
            'page'        => 'Settings',
            'title'       => 'Настройки',
            'description' => 'Настройки в приложении',
            'keywords'    => 'Настройки, приложение-задачник'
        );
    }

    /**
     * @param string $settingText
     * @param array $post
     * @return array
     */
    protected function _get($settingText, array $post) {
        $setting = $this->_utilsService->spacedStringToMethodName($settingText);
        $this->_settingsCheckPermission('read', $this->_utilsService->camelCaseToUnderline($setting, false));

        return $this->_settingsService->get($setting, $post['id']);
    }

    /**
     * @param string $settingText
     * @param array $post
     * @return array
     */
    protected function _store($settingText, array $post) {
        try {
            $this->_validatorService->check(array('intPositive' => $post['id']));
        } catch (\InvalidArgumentException $e) {
            return $this->_create($settingText, $post);
        }
        return $this->_update($settingText, $post);
    }

    /**
     * @param string $settingText
     * @param array $post
     * @return array
     */
    protected function _create($settingText, array $post) {
        $setting = $this->_utilsService->spacedStringToMethodName($settingText);
        $this->_settingsCheckPermission('create', $this->_utilsService->camelCaseToUnderline($setting, false));

        $this->_validatorService->check($this->{'_get' . ucfirst($setting) . 'Validators'}($post));

        $entity = $this->_getSettingByPost($post, 'Entity\\' . ucfirst($setting));

        $this->_settingsService->save($setting, $entity);
        return array('text' => 'Вы успешно создали ' . mb_strtolower(Service\Settings::$entitiesNames[$setting]));
    }

    /**
     * @param string $settingText
     * @param array $post
     * @return array
     */
    protected function _update($settingText, array $post) {
        $setting = $this->_utilsService->spacedStringToMethodName($settingText);
        $this->_settingsCheckPermission('update', $this->_utilsService->camelCaseToUnderline($setting, false));

        $validators = $this->{'_get' . ucfirst($setting) . 'Validators'}($post);
        $validators['settingExists'] = array($post['id'], array($setting));
        $this->_validatorService->check($validators);

        $entity = $this->_settingsService->getById($setting, $post['id']);

        $this->_setSettingByPost($post, $entity);

        $this->_settingsService->save($setting, $entity);
        return array('text' => 'Вы успешно обновили ' . mb_strtolower(Service\Settings::$entitiesNames[$setting]));
    }

    /**
     * @param string $settingText
     * @param array $post
     * @return array
     */
    protected function _delete($settingText, array $post) {
        $setting = $this->_utilsService->spacedStringToMethodName($settingText);
        $this->_settingsCheckPermission('delete', $this->_utilsService->camelCaseToUnderline($setting, false));

        $this->_validatorService->check(array('intPositiveCommaSeparated' => $post['ids']));

        $this->_settingsService->delete($setting, explode(',', $post['ids']));
        return array('text' => 'Вы успешно удалили выбранные настройки ' . mb_strtolower(Service\Settings::$entitiesNames[$setting]));
    }

    /**
     * @param array $post
     * @return array
     */
    protected function _getSettingsPermissions(array $post) {
        return $this->_permissionService->getSettingsPermissions();
    }

    /**
     * @param string $permissionCrud
     * @param string $permission
     * @throws \InvalidArgumentException
     */
    private function _settingsCheckPermission($permissionCrud, $permission) {
        if (!$this->_permissionService->getPermission('read', $permission))
            throw new \InvalidArgumentException("You have no permission to $permissionCrud $permission");
    }

    /**
     * @param array $post
     * @param string $entityClassName
     * @return mixed
     */
    private function _getSettingByPost(array $post, $entityClassName) {
        if (!class_exists($entityClassName))
            throw new \InvalidArgumentException('Setting entity class does not exists');

        $entity = new $entityClassName;
        foreach ($entity as $field=>&$value) {
            $value = $this->_utilsService->arrayGetRecursive($post, array($field));
        }
        return $entity;
    }

    /**
     * @param array $post
     * @param $entity
     */
    private function _setSettingByPost(array $post, &$entity) {
        if (empty($entity))
            throw new \InvalidArgumentException('Setting entity is empty');

        foreach ($entity as $field=>&$value) {
            if ($field === 'id')
                continue;

            $value = $this->_utilsService->arrayGetRecursive($post, array($field));
        }
    }

    /**
     * @param array $post
     * @return array
     */
    private function _getLessonValidators(array $post) {
        return array(
            'settingExists' => array(
                $post['textbookId'],
                array('textbook')
            ),
            'intPositive' => $post['number'],
            'strlen'      => array(
                $post['name'],
                array(1, 100)
            ),
            'zeroone'     => array(
                $post['isDialog'],
                $post['isRead'],
                $post['isSpeach']
            ),
        );
    }

    /**
     * @param array $post
     * @return array
     */
    private function _getMeetingValidators(array $post) {
        return array(
            'date' => $post['date']
        );
    }

    /**
     * @param array $post
     * @return array
     */
    private function _getTaskSourceValidators(array $post) {
        return array(
            'strlen'      => array(
                array($post['name'], array(1, 50)),
                array($post['shortName'], array(1, 10)),
            ),
        );
    }

    /**
     * @param array $post
     * @return array
     */
    private function _getTaskTargetDateValidators(array $post) {
        return array(
            'date' => array(
                $post['startDate'],
                $post['endDate'],
            ),
        );
    }

    /**
     * @param array $post
     * @return array
     */
    private function _getTaskTypeValidators(array $post) {
        return array(
            'strlen' => array(
                array($post['name'], array(1, 50)),
            ),
        );
    }

    /**
     * @param array $post
     * @return array
     */
    private function _getTaskDateTypeSourceCommentValidators(array $post) {
        return array(
            'settingExists' => array(
                array($post['taskTypeId'], array('taskType')),
                array($post['taskTargetDateId'], array('taskTargetDate')),
                array($post['taskSourceId'], array('taskSource')),
            ),
            'strlen' => array(
                array($post['comment'], array(1, 500)),
            ),
        );
    }

    /**
     * @param array $post
     * @return array
     */
    private function _getTextbookValidators(array $post) {
        return array(
            'intPositive' => $post['number'],
            'strlen'      => array(
                $post['name'],
                array(1, 200),
            )
        );
    }
}