<?php
namespace core;

use controllers;
use core;
use Service;

class Route {

    /**
     * Example site.ru/main/index
     */
    static function start() {

        // Defaults
        $controllerName = 'Home';
        $actionName     = 'Index';

        // Trim GET parameters
        $requestUri = strpos($_SERVER['REQUEST_URI'], '?') !== false
            ? substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?'))
            : $_SERVER['REQUEST_URI'];

        // Gets array [1] => controller, [2] => action
        $routes = explode('/', $requestUri);

        // Check controller and action
        if (!empty($routes[1])) $controllerName = ucfirst($routes[1]);
        if (!empty($routes[2])) $actionName     = ucfirst($routes[2]);

        // Get names
        $modelName = $controllerName;
        $controllerName = $controllerName;
        $actionName = 'action' . $actionName;

        $modelClassName = 'model\\' . $modelName;
        $modelClassPath = \Autoloader::getClassPath($modelClassName);

        if (file_exists($modelClassPath))
            require $modelClassPath;

        // Include controller
        $controllerClassName = 'controller\\' . $controllerName;
        $controllerClassPath = \Autoloader::getClassPath($controllerClassName);

        file_exists($controllerClassPath) ? require $controllerClassPath : Route::_errorPage404();

        $controller = new $controllerClassName;
        $action = $actionName;

        // Check action in controller
        if (method_exists($controller, $action)) {

            /** @var Service\Core\Auth $authService*/
            $authService = core\Service\ServiceLocator::authService();
            $authService->authBySession();

            // Вызываем метод экшна (действия)
            $controller->$action();
            return;
        }

        Route::_errorPage404();
    }

    private function _errorPage404() {
        $host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
        header('Location:' . $host . 'error404');
    }
}