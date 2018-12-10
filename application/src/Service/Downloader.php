<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 06.07.2017
 * Time: 16:18
 */

namespace Service;

use core\Service\ServiceLocator;
use Service;
use model\Def;

class Downloader extends Basic {

    const EXT_IMG        = array('jpg', 'jpeg', 'gif', 'png');
    const MIME_IMG_TYPES = array('image/jpg', 'image/jpeg', 'image/gif', 'image/png');

    /**
     * Service\Utils
     */
    private $_utilsService;

    /**
     * Service\Path
     */
    private $_pathService;

    function __construct() {
        $this->_initServices();
    }

    private function _initServices() {
        $this->_utilsService = ServiceLocator::utilsService();
        $this->_pathService  = ServiceLocator::pathService();
    }

    /**
     * @param string $fileFieldName
     * @param string $tempName
     * @return string
     */
    public function downloadUserImage($fileFieldName, $tempName) {

        $ext  = $this->_utilsService->getExtention($_FILES[$fileFieldName]['name']);
        $path = $this->_pathService->getTempUserImagePath($tempName, $ext);

        copy($_FILES[$fileFieldName]['tmp_name'], $path);

        return $path;
    }
}