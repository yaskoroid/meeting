<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 27.12.2018
 * Time: 16:09
 */

namespace model;

use core\Service\ServiceLocator;
use Service;
use Service\User;

class EntityFile extends Model {

    /**
     * @var Service\Permission
     */
    private $_permissionService;

    /**
     * @var Service\Validator
     */
    private $_validatorService;

    /**
     * @var Service\Downloader
     */
    private $_downloaderService;

    /**
     * @var Service\Entity\File
     */
    private $_fileService;

    /**
     * @var Service\Entity\Base
     */
    private $_entityBaseService;

    function __construct() {
        parent::__construct();
    }

    protected function _initAjaxServices() {
        $this->_permissionService = ServiceLocator::permissionService();
        $this->_validatorService  = ServiceLocator::validatorService();
        $this->_downloaderService = ServiceLocator::downloaderService();
        $this->_fileService       = ServiceLocator::fileService();
        $this->_entityBaseService = ServiceLocator::entityBaseService();
    }

    protected function _initRenderServices() {}

    protected function _initRenderData() {}

    /**
     * @param string $entityText
     * @param array $post
     * @return array
     */
    protected function _get($entityText, array $post) {
        $entityName = ucfirst($this->_utilsService->spacedStringToMethodName($entityText));

        $this->_entityCheckPermission('read', $this->_utilsService->camelCaseToUnderline($entityName, false));

        $fileField = $this->_entityBaseService->getFileField($entityName, $post['field']);

        $isOneFileField = $this->_entityBaseService->isOneFileField($fileField);

        $this->_validatorService->check(
            array(
                'intPositiveCommaSeparated' => $post['ids']
            )
        );
        $entities = $this->_entityBaseService->getByIds($entityName, explode(',', $post['ids']));

        if (count($entities) === 0)
            return array();

        $filesIds = array();
        foreach ($entities as $entity) {
            if ($isOneFileField) {
                array_push($filesIds, $entity->{$fileField});
                continue;
            }
            array_merge($filesIds, explode(',', $entity->{$fileField}));
        }

        if (count($filesIds) === 0)
            return array();

        $files = $this->_entityBaseService->getByIds('File', array_unique($filesIds, SORT_NUMERIC));

        if (count($files) === 0)
            return array();

        $this->_entityBaseService->filterPublicEntityFields('File', $files);

        return $this->_settingsService->get($entity, $post['id']);
    }

    /**
     * @param string $entityText
     * @param array $post
     * @return array
     */
    protected function _store($entityText, array $post) {
        $entityName = ucfirst($this->_utilsService->spacedStringToMethodName($entityText));

        $this->_entityCheckPermission('update', $this->_utilsService->camelCaseToUnderline($entityName, false));

        $fileField = $this->_entityBaseService->getFileField($entityName, $post['field']);

        $isOneFileField = $this->_entityBaseService->isOneFileField($fileField);

        $validators = array(
            'intPositive' => array(
                $post['id']
            ),
        );

       $isOneFileField
            ? $validators['intPositiveCommaSeparated'] = $post['filesIds']
            : array_push($validators['intPositive'], $post['fileId']);

        $this->_validatorService->check($validators);

        try {

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
        $entityName = ucfirst($this->_utilsService->spacedStringToMethodName($settingText));


        $this->_validatorService->check($this->{'_get' . $entityName . 'Validators'}($post));

        $entity = $this->_getSettingByPost($post, 'Entity\\' . $entityName);

        $this->_settingsService->save($entityName, $entity);
        return array(
            'text' => 'Вы успешно создали файл ' .
                mb_strtolower($this->_settingsService->getEntityName($entityName))
        );
    }

    /**
     * @param string $settingText
     * @param array $post
     * @return array
     */
    protected function _update($settingText, array $post) {
        $setting = ucfirst($this->_utilsService->spacedStringToMethodName($settingText));
        $this->_entityCheckPermission('update', $this->_utilsService->camelCaseToUnderline($setting, false));

        $validators = $this->{'_get' . ucfirst($setting) . 'Validators'}($post);
        $validators['settingExists'] = array($post['id'], array($setting));
        $this->_validatorService->check($validators);

        $entity = $this->_settingsService->getById($setting, $post['id']);

        $this->_setSettingByPost($post, $entity);

        $this->_settingsService->save($setting, $entity);
        return array(
            'text' => 'Вы успешно обновили ' .
                mb_strtolower($this->_settingsService->getEntityName($setting))
        );
    }

    /**
     * @param string $settingText
     * @param array $post
     * @return array
     */
    protected function _delete($settingText, array $post) {
        $setting = ucfirst($this->_utilsService->spacedStringToMethodName($settingText));
        $this->_entityCheckPermission('delete', $this->_utilsService->camelCaseToUnderline($setting, false));

        $this->_validatorService->check(array('intPositiveCommaSeparated' => $post['ids']));

        $this->_settingsService->deleteByIds($setting, explode(',', $post['ids']));
        return array(
            'text' => 'Вы успешно удалили выбранные настройки ' .
                mb_strtolower($this->_settingsService->getEntityName($setting))
        );
    }

    /**
     * @param string $permissionCrud
     * @param string $permission
     * @throws \InvalidArgumentException
     */
    private function _entityCheckPermission($permissionCrud, $permission) {
        if (!$this->_permissionService->getPermission($permissionCrud, $permission))
            throw new \InvalidArgumentException("You have no permission to $permissionCrud $permission");
    }

    protected function _files($settingText, array $post) {
        $setting = ucfirst($this->_utilsService->spacedStringToMethodName($settingText));
        $this->_entityCheckPermission('update', $this->_utilsService->camelCaseToUnderline($setting, false));

        $validators['settingExists'] = array($post['id'], array($setting));

        $this->_validatorService->check($validators);

        $entity = $this->_settingsService->getById($setting, $post['id']);
        $entityFilesIds = explode(',', $this->_utilsService->arrayGetRecursive($entity, array('filesIds')));

        if (count($entityFilesIds) + count($_FILES) > 450)
            throw new \InvalidArgumentException('More than 450 related files');

        $filesIdsToDelete = array();
        $filesDescriptionByFileId = array();
        if ($entityFilesIds !== null) {

            $this->_validatorService->check(array('intPositiveCommaSeparated' => $entityFilesIds));

            foreach ($entityFilesIds as $entityFileId) {
                $fileDescriptionPostField = $post['fileDescription_' . $entityFileId];
                if (isset($post[$fileDescriptionPostField])) {
                    $fileDescription = $post[$fileDescriptionPostField];
                    $this->_validatorService->check(
                        array(
                            'strlen' => array(
                                $fileDescription,
                                array(1, 300)
                            )
                        )
                    );
                    $filesDescriptionByFileId[$entityFileId] = $fileDescription;
                } else
                    array_push($filesIdsToDelete, $entityFileId);
            }
        }

        foreach ($_FILES as $name => $file) {
            $newFileDescriptionPostField = $post[$name . 'Description'];

            if (!isset($post[$newFileDescriptionPostField]))
                throw new \InvalidArgumentException('No file description to ' . $name);

            $fileDescription = $post[$fileDescriptionPostField];
            $this->_validatorService->check(
                array(
                    'strlen' => array(
                        $fileDescription,
                        array(1, 300)
                    )
                )
            );
        }

        if ($entityFilesIds !== null) {

            $this->_validatorService->check(array('intPositiveCommaSeparated' => $entityFilesIds));

            foreach ($entityFilesIds as $entityFileId) {
                $file = $this->_fileService->getById($entityFileId);
                if ($file === null)
                    continue;

                $fileDescriptionPostField = $post['fileDescription_' . $entityFileId];
                if (isset($post[$fileDescriptionPostField])) {
                    $fileDescription = $post[$fileDescriptionPostField];
                    $this->_validatorService->check(
                        array(
                            'strlen' => array(
                                $fileDescription,
                                array(1, 300)
                            )
                        )
                    );
                    $file->description = $fileDescription;
                    $this->_fileService->save($file);
                } else {
                    $this->_fileService->deleteIfNotUsedAnywhere($filesIdsToDelete);
                }
            }
        }


        if (count($_FILES) === 0)
            return;

        foreach ($_FILES as $name => $file) {
            $settingTempFile = $this->_downloaderService->downloadFileToTemp(
                'document',
                $fileDescription,
                $name
            );

            $settingFile = $this->_downloaderService->storeFromTemp($settingTempFile->id);
            $entityFilesIds .= ',' . $settingFile->id;

            $entity->filesIds = $entityFilesIds;
            $this->_settingsService->save($entity);
        }
    }
}