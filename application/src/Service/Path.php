<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 10.12.2018
 * Time: 11:39
 */

namespace Service;

use core\Service\ServiceLocator;
use Service;
use model\Def;

class Path extends Basic {

    /**
     * Service\Utils
     */
    private $_utilsService;

    function __construct() {
        self::_initServices();
    }

    private function _initServices() {
        $this->_utilsService   = ServiceLocator::utilsService();
    }

    /**
     * @param string $path
     * @param bool|false $isRelative
     * @return string
     */
    public function adapterFromHttpAccess($path, $isRelative = false) {
        if (strpos($path, DIRECTORY_SEPARATOR) === false)
            throw new \InvalidArgumentException('No directories in path');

        $path = str_replace(DIRECTORY_SEPARATOR, '/', $path);

        if (strpos($path, $GLOBALS['config']['path']['projectFolderName']) === false)
            throw new \InvalidArgumentException('No folder of project');

        $path = substr($path, strpos($path, $GLOBALS['config']['path']['projectFolderName']));
        return $isRelative
            ? $GLOBALS['site']['http'] . '://' . $path
            : substr($path, strpos($path, '/') + 1);
    }

    /**
     * @return string
     */
    /*public function getTempPath() {
        return $GLOBALS['config']['path']['file'] . DIRECTORY_SEPARATOR . 'temp';
    }*/

    /**
     * @param string $fileName
     * @param string $fileExtension
     * @return string
     */
   /* public function getTempFilePath($fileName, $fileExtension) {
        return $this->getTempPath() . DIRECTORY_SEPARATOR . $fileName . '.' . $fileExtension;
    }*/

    /**
     * @param string $fileType
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getFileTypePath($fileType) {
        $fileTypeExtensionsAllowed = $this->_utilsService->arrayGetRecursive(
            Service\Entity\File::TYPE,
            array($fileType)
        );
        if ($fileTypeExtensionsAllowed === null)
            throw new \InvalidArgumentException('No such file type');

        return $GLOBALS['config']['path']['file'] . DIRECTORY_SEPARATOR . $fileType;
    }

    /**
     * @param string $fileType
     * @param string $fileName
     * @param string $fileExtension
     * @return string
     */
    public function getFileTypeFilePath($fileType, $fileName, $fileExtension) {
        return $this->getFileTypePath($fileType) . DIRECTORY_SEPARATOR . $fileName . '.' . $fileExtension;
    }

    /**
     * @param string $filePath
     * @return string
     */
    public function getFileExtension($filePath) {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        return $extension === null ? '' : $extension;
    }

    /**
     * @param string $filePath
     * @return string
     */
    public function getFileName($filePath) {
        return pathinfo($filePath, PATHINFO_FILENAME);
    }
}