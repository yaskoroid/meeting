<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 20.12.2018
 * Time: 16:39
 */

namespace Service\Entity;

use core\Service\ServiceLocator;
use Service;
use Entity as Ent;

class File extends Base {

    const TYPE = array(
        'image' => array(
            'jpg',
            'jpeg',
            'gif',
            'png'
        ),
        'document' => array(
            'doc',
            'docx',
            'xls',
            'xlsx',
            'pdf',
            'txt',
            'jpg',
            'jpeg',
            'gif',
            'png'
        ),
        'temp' => array(
            'tmp'
        ),
        'etc' => array(

        )
    );
    const MIME_DESCRIPTION = array(
        'jpg'  => 'Картинка JPG',
        'jpeg' => 'Картинка JPEG',
        'gif'  => 'Картинка GIF',
        'png'  => 'Картинка PNG',
        'doc'  => 'Документ Microsoft Word',
        'docx' => 'Документ Microsoft Word',
        'xls'  => 'Документ Microsoft Excel',
        'xlsx' => 'Документ Microsoft Excel',
        'pdf'  => 'Документ PDF',
        'txt'  => 'Текстовый документ',
    );
    const MIME_TYPE = array(
        'jpg'  => 'image/jpg',
        'jpeg' => 'image/jpeg',
        'gif'  => 'image/gif',
        'png'  => 'image/png',
        'doc'  => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls'  => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'pdf'  => 'application/pdf',
        'txt'  => 'text/plain',
    );
    const FILES_IS_ENTITIES = array(
        'user' => array(
            'service'         => 'userProfile',
            'isServiceCommon' => 'userProfile',
            'field'           => 'imageFileId',
            'isSingle'        => true,
        ),
        'lesson' => array(
            'service'  => 'setting',
            'field'    => 'filesIds',
            'isSingle' => false,
        ),
    );

    /**
     * Service\Path
     */
    private $_pathService;

    function __construct() {
        parent::__construct();
        $this->_initServices();
    }

    private function _initServices() {
        $this->_pathService    = ServiceLocator::pathService();
    }

    /**
     * @param Ent\File $file
     * @return string
     */
    public function getFileName($file) {
        if (empty($file->extension))
            return $file->name;

        return "$file->name.$file->extension";
    }

    /**
     * @param string $name
     * @param string $extension
     * @param string $type
     * @param string $description
     * @param string bool|false $isTemp
     * @return Ent\File
     */
    public function instance($name, $extension, $type, $description, $isTemp = false) {
        $file = new Ent\File();

        $file->type        = $type;
        $file->description = $description;
        $file->name        = $name;
        $file->extension   = $extension;
        $file->isTemp      = $isTemp;

        $this->_setMimeByExtension($file);
        return $file;
    }

    /**
     * @param Ent\File $file
     */
    public function checkFile($file) {
        $this->_checkExtension($file->extension, $file->type);
        $this->_checkMime($this->getPath($file), $file->type);
    }

    /**
     * @param Ent\File $file
     */
    public function getPath($file) {
        return $this->_pathService->getFileTypeFilePath(
            $file->isTemp ? 'temp' : $file->type,
            $file->name,
            $file->isTemp ? 'tmp' : $file->extension
        );
    }

    /**
     * @param string $filePath
     * @return bool
     */
    public function deleteFile($filePath) {
        if ($filePath !== null && file_exists($filePath))
            return unlink($filePath);
    }

    /**
     * @param int $id
     * @return Ent\File
     */
    /*public function getById($id) {
        return $this->_meetingService->getFileById($id);
    }*/

    /**
     * @param int $ids
     * @return Ent\File[]
     */
   /* public function getByIds($ids) {
        return $this->_meetingService->getFilesByIds($ids);
    }*/

    /**
     * @param Ent\File $file
     */
   /* public function save($file) {
        $this->_meetingService->saveFile($file);
    }*/

    /**
     * @param array $ids
     */
    public function deleteByIdsAndStorage(array $ids) {
        $filesToDelete = $this->getByIds($ids);
        if (count($filesToDelete) === 0)
            return;

        foreach ($filesToDelete as $fileToDelete) {
            $this->deleteFile($this->getPath($fileToDelete));
        }

        $this->delete($filesToDelete);
    }

    public function deleteIfNotUsedAnywhere($ids) {
        $filesToDelete = $this->_meetingService->getFilesByIds($ids);
        if (count($filesToDelete) === 0)
            return;

        /*foreach (self::FILES_IS_ENTITIES) {

        }*/

    }

    /**
     * @param Ent\File[] $files
     */
    public function filterPublicFilesFields(&$files) {
        foreach ($files as $file) {
            $this->filterPublicFileFields($file);
        }
    }

    /**
     * @param Ent\File $file
     */
    public function filterPublicFileFields(&$file) {
        $publicFields = $this->getEntityPublicFields($this->_getClass());
        foreach ($file as $fileField => $fileValue) {
            if (!in_array($fileField, $publicFields))
                unset($file->{$fileField});
        }
    }

    /**
     * @param Ent\File $file
     */
    private function _setMimeByExtension(&$file) {
        $file->mime = $this->_utilsService->arrayGetRecursive(
            self::MIME_TYPE,
            array($file->extension)
        );
        $file->mimeDescription = $this->_utilsService->arrayGetRecursive(
            self::MIME_DESCRIPTION,
            array($file->extension)
        );
    }

    /**
     * @param string $extension
     * @param string $fileType
     */
    private function _checkExtension($extension, $fileType) {

        $fileTypeExtensionsAllowed = $this->_utilsService->arrayGetRecursive(self::TYPE, array($fileType));
        if ($fileTypeExtensionsAllowed === null)
            throw new \InvalidArgumentException('No such file type');

        if (!in_array($extension, $fileTypeExtensionsAllowed))
            throw new \InvalidArgumentException("Bad extension for file type '$fileType'");

    }

    /**
     * @param string $filePath
     * @param string $fileType
     */
    private function _checkMime($filePath, $fileType) {

        $fileTypeExtensionsAllowed = $this->_utilsService->arrayGetRecursive(self::TYPE, array($fileType));
        if ($fileTypeExtensionsAllowed === null)
            throw new \InvalidArgumentException('No such file type');

        $fileTypeMimesAllowed = array_intersect_key(
            Service\Entity\File::MIME_TYPE,
            array_flip($fileTypeExtensionsAllowed)
        );

        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $t = finfo_file($fileInfo, $filePath);
        if (!in_array($t, $fileTypeMimesAllowed)) {
            finfo_close($fileInfo);
            throw new \InvalidArgumentException("Bad mime for file type '$fileType'");
        }
        finfo_close($fileInfo);
    }
}