<?php
namespace core;

use core\Service\ServiceLocator;
use model\Def;
use Service;

class View
{
    /**
     * @var Service\Context
     */
    private $_contextService;

    function __construct() {
        $this->_initServices();
    }
    /**
     * @param string $contentView - dynamic content of view page
     * @param string $templateView - template which surrounds the page
     * @param array $data - data of model
     */
    function generate($contentView, $templateView, array $data = array())
    {
        $def = $this->_getDef($contentView);

        // Изменим знчения так, чтобы получить имена файлов
        $contentView  = $contentView . ".php";
        $templateView = $templateView . ".php";

        // Если в данном view используется модель, то переменная $data не пустая
        if (!empty($data)) {
            // Извлечем данные массива в одноименные переменные
            if (is_array($data)) {
                extract($data);
            }
        }

        // Подключаем файл
        require "application/template/View/" . $templateView;
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

    private function _initServices() {
        $this->_contextService = ServiceLocator::contextService();
    }
}