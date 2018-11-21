<?php
namespace core;

use core\Service\ServiceLocator;
use model\Def;
use Service;

class View {

    /**
     * @var Service\Context
     */
    private $_contextService;

    /**
     * @var Service\Context
     */
    private $_templateService;

    function __construct() {
        $this->_initServices();
    }

    private function _initServices() {
        $this->_contextService  = ServiceLocator::contextService();
        $this->_templateService = ServiceLocator::templateService();
    }

    /**
     * @param string $derivedView  - dynamic content of view page
     * @param string $baseView - template which surrounds the page
     * @param array $data - data of model
     */
    function generate($derivedView, $baseView, array $data = array())
    {
        $def = $this->_getDef($contentView);

        $derivedView  = $derivedView . ".php";
        $baseView = $baseView . ".php";

        $isModelData = !empty($data);
        if ($isModelData) {
            if (is_array($data)) {
                extract($data);
            }
        }

        // Подключаем файл
        require "application/template/View/" . $baseView;
    }

    /**
     * Function shows content of data array like a JSON text
     * @param array $data
     */
    function generateJson(array $data = array()) {
        // Подключаем файл
        require "application/template/View/Ajax.php";
    }

    /**
     * Function returns Defaults object
     * @param string $view
     * @return Def|null
     */
    private function _getDef($view) {
        // Подключаем файл
        $defClassName = 'model\\Def\\' . $view;
        $defFileName = \Autoloader::getClassPath($defClassName);
        if (file_exists($defFileName)) {
            return new $defClassName;
        }
        return null;
    }
}