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
use Entity;

class Downloader extends Basic {

    /**
     * Service\Utils
     */
    private $_utilsService;

    /**
     * Service\Path
     */
    private $_pathService;

    /**
     * Service\Entity\File
     */
    private $_fileService;

    function __construct() {
        $this->_initServices();
    }

    private function _initServices() {
        $this->_utilsService = ServiceLocator::utilsService();
        $this->_pathService  = ServiceLocator::pathService();
        $this->_fileService  = ServiceLocator::fileService();
    }

    /**
     * @param string $type
     * @param string $description
     * @param string $fileFieldName
     * @return Entity\File
     * @throws \Exception
     * @throws \InvalidArgumentException
     * @throws \Throwable
     */
    public function downloadFileToTemp($type, $description, $fileFieldName) {

        if (empty($_FILES[$fileFieldName]))
            throw new \InvalidArgumentException('No file to download');

        $phpTempFileDestination = $_FILES[$fileFieldName]['tmp_name'];
        if (!file_exists($phpTempFileDestination))
            throw new \RuntimeException('File to make temporary does not exists in server temp folder');

        $extension = $this->_pathService->getFileExtension($_FILES[$fileFieldName]['name']);
        $file = $this->_fileService->instance(
            $this->_utilsService->createRandomHash32(),
            $extension,
            $type,
            $description,
            true
        );
        $pathReal = $this->_fileService->getPath($file);

        try {
            $isCopied = copy($phpTempFileDestination, $pathReal);
            if (!$isCopied)
                throw new \RuntimeException('Could not copy file');

            $this->_fileService->checkFile($file);
            $this->_fileService->save($file);
            return $file;
        } catch (\Exception $e) {
            $this->_fileService->deleteFile($pathReal);
            throw new \Exception($e->getMessage());
        }  catch (\InvalidArgumentException $eInvalid) {
            $this->_fileService->deleteFile($pathReal);
            throw new \InvalidArgumentException($eInvalid->getMessage());
        } catch (\Throwable $t) {
            $this->_fileService->deleteFile($pathReal);
            throw $t;
        }
    }

    public function storeFromTemp($fileId) {
        $file = $this->_fileService->getById($fileId);

        if (!$file->isTemp)
            throw new \InvalidArgumentException('File is not temporary');

        $pathReal = $this->_fileService->getPath($file);
        if (!file_exists($pathReal))
            throw new \RuntimeException('Temporary file to copy does not exists');

        $file->isTemp = false;
        $path = $this->_fileService->getPath($file);
        try {
            $isCopied = copy($pathReal, $path);
            if (!$isCopied)
                throw new \RuntimeException('Could not copy file');

            $this->_fileService->deleteFile($pathReal);
            $this->_fileService->save($file);
            return $file;
        } catch (\Exception $e) {
            $this->_fileService->deleteFile($pathReal);
            $this->_fileService->deleteFile($path);
            throw new \Exception($e->getMessage());
        }  catch (\InvalidArgumentException $eInvalid) {
            $this->_fileService->deleteFile($pathReal);
            $this->_fileService->deleteFile($path);
            throw new \InvalidArgumentException($eInvalid->getMessage());
        } catch (\Throwable $t) {
            $this->_fileService->deleteFile($pathReal);
            $this->_fileService->deleteFile($path);
            throw $t;
        }
    }
}