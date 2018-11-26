<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 20.11.2018
 * Time: 16:01
 */

namespace Service;

use Twig_Loader_Filesystem;
use Twig_Environment;
use Twig_Extension_Debug;
use Service;

class Template extends Basic {

    public function __construct() {
        require_once $GLOBALS['config']['autoload']['Twig-2.5.0'];
    }

    /**
     * Renders template.
     *
     * @param string $templateName
     * @param Array $variables Params
     *      * @param string $templateType
     //* @param Controller $controller
     *
     * @return String HTML or JSON or some else
     */
    public function render($templateName, $variables = array(), $templateType = 'view' /*, $controller = null*/) {
        $loader = new Twig_Loader_Filesystem($GLOBALS['config']['paths']['templates'][$templateType]);
        $twig   = new Twig_Environment($loader, array(
            'cache'         => 'twig_compilation_cache',
            'auto_reload'   => true,
            'debug'         => true,
        ));
        $twig->addExtension(new Twig_Extension_Debug());
        return $twig->render($templateName, $variables);
    }
}