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

class Downloader extends Basic {

    const EXT_IMG        = array('jpg', 'jpeg', 'gif', 'png');
    const MIME_IMG_TYPES = array('image/jpg', 'image/jpeg', 'image/gif', 'image/png');

    /**
     * @var Service\Validator
     */
    private $_validatorService;

    function __construct() {
        self::_initServices();
    }

    private function _initServices() {
        $this->_validatorService = ServiceLocator::validatorService();
    }

    public function downloadImage($postFieldName, $newName) {
        $path = $_SERVER['DOCUMENT_ROOT'] .
            Downloader::IMG_USER_FOLDER . '/' . $newName;

        if (!array_key_exists($postFieldName, $_FILES))
            throw new \InvalidArgumentException('No file to download');

        copy($_FILES[$postFieldName]['tmp_name'], $path);

        try {
            $this->_validatorService->check(array('mimeImage' => $path));
        } catch (\InvalidArgumentException $e) {
            unlink($this->path);
            throw new \InvalidArgumentException($e->getMessage());
        }

        return $path;
    }
}