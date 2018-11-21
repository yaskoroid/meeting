<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 06.11.2018
 * Time: 11:08
 */

/**
 * Class Autoloader
 * @package application
 */
class Autoloader
{
    /**
     * @var string
     */
    private static $_lastLoadedFilename;

    /**
     * @var array
     */
    private static $_namespacesMustBe = array(
        'model'       => 'application\\model',
        'core'        => 'application\\core',
        'component'   => 'application\\component',
        'controller'  => 'application\\controller',
        'entity'      => 'application\\src\\Entity',
        'service'     => 'application\\src\\Service',
        'respect'     => 'vendor\\Relational\\library\\Respect',
    );

    public function __construct()
    {
        spl_autoload_register(array($this, '__autoloadApplication'));
    }

    /**
     * @param string $className
     * @return string
     */
    public static function getClassPath($className) {
        $className = self::_getNamespaceMustBe($className);
        $pathParts = explode('\\', $className);
        $projectPath = substr(dirname(__FILE__), 0, strrpos(dirname(__FILE__), DIRECTORY_SEPARATOR));
        return $projectPath . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $pathParts) . '.php';
    }

    /**
     * @param string $className
     * @return string
     */
    private static function _getNamespaceMustBe($className) {
        $pathParts = explode('\\', $className);
        $firstNameSpace = strtolower($pathParts[0]);
        if (array_key_exists($firstNameSpace, self::$_namespacesMustBe)) {
            unset($pathParts[0]);
            $className = self::$_namespacesMustBe[$firstNameSpace] . '\\' . implode('\\', $pathParts);
        }
        return $className;
    }

    /**
     * @param string $className
     */
    private function __autoloadApplication($className)
    {
        self::$_lastLoadedFilename = self::getClassPath($className);
        if (!empty(self::$_lastLoadedFilename)) {
            if (file_exists(self::$_lastLoadedFilename)) {
                require_once(self::$_lastLoadedFilename);
            }
        }
    }
}

$autoloader = new Autoloader();
