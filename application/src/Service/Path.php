<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 10.12.2018
 * Time: 11:39
 */

namespace Service;

use model\Def;

class Path extends Basic {

    function __construct() {
        self::_initServices();
    }

    private function _initServices() {
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
    public function getEtcPath() {
        return $GLOBALS['config']['path']['file'] . DIRECTORY_SEPARATOR . 'image/etc';
    }

    /**
     * @return string
     */
    public function getTempUserImagePath() {
        return $GLOBALS['config']['path']['file'] . DIRECTORY_SEPARATOR . 'image/user/temp';
    }

    /**
     * @param string $tempName
     * @param string $extension
     * @return string
     */
    public function getTempUserImageFilePath($tempName, $extension) {
        return $this->getTempUserImagePath() . DIRECTORY_SEPARATOR . $tempName . '.' . $extension;
    }

    /**
     * @return string
     */
    public function getUserImagePath() {
        return $GLOBALS['config']['path']['file'] . DIRECTORY_SEPARATOR . 'image/user';
    }

    /**
     * @param string $userId
     * @param string $extension
     * @return string
     */
    public function getUserImageFilePath($userId, $extension) {
        return $this->getUserImagePath() . DIRECTORY_SEPARATOR . $userId . '.' . $extension;
    }
}