<?php
namespace core;

use controllers;
use core;
use Service;

/*
 * Класс занимающийся запросами пользователя и перенаправлениями
 */
class Route
{
    /*
     * Функция начинает роутинг используя параметры из
     * запроса пользователя, разделенные слэшем.
     * Например site.ru/main/index
     */
    static function start()
    {

        // Значения имени контроллера и действия по умолчанию
        $controllerName = 'Home';
        $actionName = 'Index';

        // Отбрасываем GET параметры
        $requestUri = strpos($_SERVER['REQUEST_URI'], '?') !== false
            ? substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?'))
            : $_SERVER['REQUEST_URI'];

        // Получаем массив [1] => контроллер, [2] => действие
        $routes = explode('/', $requestUri);

        // Проверяем не пустое ли первое значение (контроллера)
        if (!empty($routes[1])) {
            // Делаем первую букву имени контроллера заглавной
            $controllerName = ucfirst($routes[1]);
        }

        // Проверяем не пустое ли второе значение (экшн)
        if (!empty($routes[2])) {
            // Делаем первую букву имени действия заглавной
            $actionName = ucfirst($routes[2]);
        }

        //Определяем имена классов модели, контроллера и метода действия
        $modelName = 'Model' . $controllerName;
        $controllerName = 'Controller' . $controllerName;
        $actionName = 'action' . $actionName;

        $modelClassName = 'model\\' . $modelName;
        $modelClassPath = \Autoloader::getClassPath($modelClassName);

        // Подключаем файл модели, если он существует
        if (file_exists($modelClassPath)) {
            require $modelClassPath;
        }

        //Подключаем файл контроллера, если он не существует, то 404
        $controllerClassName = 'controller\\' . $controllerName;
        $controllerClassPath = \Autoloader::getClassPath($controllerClassName);

        if (file_exists($controllerClassPath)) {
            require $controllerClassPath;
        } else {
            Route::errorPage404();
        }

        // Создадим экземпляр контроллера
        $controller = new $controllerClassName;
        $action = $actionName;

        // Проверяем существует ли экшн, если он не существует, то 404
        if (method_exists($controller, $action)) {


            /** @var Service\Core\Auth $authService*/
            $authService = core\Service\ServiceLocator::authService();
            $authService->authBySession();

            // Вызываем метод экшна (действия)
            $controller->$action();
        } else {
            Route::errorPage404();
        }
    }

    /*
     * Функция отображает страницу 404
     */
    private function errorPage404()
    {
        $host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
        header('Location:' . $host . '404');
    }
}