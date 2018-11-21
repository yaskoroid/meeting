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
use Service;


class Template extends Basic {

    public function __construct() {
        parent::__construct();
        require_once $GLOBALS['config']['paths']['vendor'] . $GLOBALS['config']['autoload']['Twig-2.5.0'];
        \Twig_Autoloader::register();
    }

    /**
     * Renders template.
     *
     * @param $templateName
     * @param Array $variables Params
     * @param Controller $controller
     *
     * @return String HTML or JSON or some else
     */
    public function render($templateName, $variables = array(), $controller = null) {
        $loader = new Twig_Loader_Filesystem($GLOBALS['config']['paths']['templates']);
        $twig   = new Twig_Environment($loader, array(
            'cache'         => 'twig_compilation_cache',
            'autoescape'    => true,
            'auto_reload'   => true,
        ));
        $variables['controller'] = $controller;
        $variables['c'] = $controller;
        $twig->addExtension(new Service\Twig\Extension());
        return $twig->render($templateName, $variables);
    }
}