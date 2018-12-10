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
     * @param string $tempName
     * @param string $extension
     * @return string
     */
    public function getTempUserImagePath($tempName, $extension) {
        return $_SERVER['DOCUMENT_ROOT'] . Def\User::$constImageUserTempPath . DIRECTORY_SEPARATOR .
            $tempName . '.' . $extension;
    }

    /**
     * @param string $userId
     * @param string $extension
     * @return string
     */
    public function getUserImagePath($userId, $extension) {
        return $_SERVER['DOCUMENT_ROOT'] . Def\User::$constImageUserPath . DIRECTORY_SEPARATOR .
            $userId . '.' . $extension;
    }
}