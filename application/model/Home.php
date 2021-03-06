<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 03.07.2017
 * Time: 19:41
 */

namespace model;

use core;

class Home extends Model
{

    // Максимальная длинна задачи в симв
    public $taskLenght = 1000;

    // Размеры изображениий задач
    private $imageWidth = 320;
    private $imageHeight = 240;

    private $countRowsOnPage = 3; // Количество задач на странице
    private $countRows; // Количество строк в таблице задач всего
    private $pageNumber = 1; // Текущая отображаемая страница
    private $pageCount = 0; // Количество страниц
    private $n = 2; // Количество страниц с каждой стороны от текущей (целое больше 1)
    private $sort = 3; // Сортировка
    private $pagesArray = array(); // Массив для отображения ссылок на страницы в виде

    // Значения сортировок
    private $sortings = array(
        "pageHomeSessionSortLoginUp" => "ORDER BY login",
        "pageHomeSessionSortLoginDown" => "ORDER BY login DESC",
        "pageHomeSessionSortEmailUp" => "ORDER BY email",
        "pageHomeSessionSortEmailDown" => "ORDER BY email DESC",
        "pageHomeSessionSortDoneUp" => "ORDER BY done",
        "pageHomeSessionSortDoneDown" => "ORDER BY done DESC"
    );

    // Значение ключа массива сессии со значением сортировки
    private $pageSorting = "pageHomeSessionSorting";

    // Значение ключа массива сессии со значением текущей страницы
    private $pageNumberPrefix = "pageHomeSessionPageNumber";

    // Массив логинов всех пользователей
    private $users = array();

    // Результат добавления задачи
    private $resultOfAddTask = null;

    /*
     * В конструкторе определяем количество строк таблицы задач
     * и всех пользователей с их id
     */
    function __construct()
    {
        parent::__construct();
        // Определяем пользователей
        $this->getUsers();

        // определяем количество страниц и строк таблицы задач
        $this->initCountOfRowsAndPages();
    }

    protected function _initAjaxServices() {}

    protected function _initRenderServices() {}

    protected function _initRenderData() {
        $this->_result = array(
            'page'        => 'Home',
            'title'       => 'Приложение-задачник',
            'description' => 'Приложение-задачник',
            'keywords'    => 'Web приложение-задачник'
        );
    }

    private function getUsers() {
        // Обращаемся к БД
       /* $db_result = DbProvider::query("SELECT id,login FROM \"user\" ORDER BY login");
        // Проверяем результат
        if (empty($db_result['error'])) {
            // Присваимаем значения
            while ($myrow = $db_result['content']->fetch_assoc()) {
                $this->users[$myrow['id']] = $myrow['login'];
            }
        } else {
            $this->users = $db_result;
        }*/
    }

    /*
     * Функция записывает в БД новую задачу
     */
    public function createTask($post)
    {
        // Инициализируем значения пользователя и задачи (для исключения ошибок в IDE)
        $idUser = '';
        $task = '';

        // Извлекаем данные из $_POST
        extract($post);

        // Авторизирован ли пользователь
        /*
         * Нужно раскомментировать, если нужно, чтобы только
         * авторизированные пользователи могли добавлять задачи
         */
        // if ($_SESSION['unregistered'] != 1) {

            // Существует ли id пользователя, которое прислал юзер
            if (array_key_exists($idUser, $this->users)) {

                // Инициализируем подключение к БД здесь так как для
                // "checkInjection" нужно действующее соединение в БД
                $dbProvider = new DbProvider();

                // Проверяем поле задачи на SQL-инъекцию
                if ($task = $dbProvider->checkInjection($task)) {

                    // Проверка расширения файла пользователя
                    if (Helper::checkExtention($_FILES['image']['name'],array("jpg","jpeg","gif","png"))) {
                        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

                        // Вставляем данные в БД
                        $db_result = $dbProvider->queryThis("
INSERT INTO
task (id_user,task,ext,done)
VALUES ('$idUser','" .htmlspecialchars($task)."','".$ext."',0)");

                        // Проверяем результат вставки
                        if (empty($db_result['error'])) {

                            // Получаем id добавленной задачи
                            $id = $dbProvider->getLastAutoinctement();

                            // Если id не null
                            if ($id) {

                                // Начинаем загружать файл картинки
                                $downloader = new
                                    Downloader(Downloader::IMG_TYPES, "image",
                                        "task_" . $id . "." . $ext);
                                $resultDownload = $downloader->download();

                                // Проверяем результат загрузки и проверки типа
                                // уже загруженного файла по MIME
                                if ($resultDownload['error'] == null) {

                                    // Изменяем размер
                                    $instImage = new Image($this->imageWidth, $this->imageHeight);
                                    $resultResize = $instImage->
                                        imageResizeProportional($resultDownload['path']);

                                    // Проверяем результат
                                    if (empty($resultResize['error'])) {
                                        // Переопределяем количество страниц и строк в таблице задач
                                        $this->initCountOfRowsAndPages();
                                        $this->resultOfAddTask = array(
                                            "error" => null,
                                            "response" => "The task successfully added");
                                    } else {
                                        $this->resultOfAddTask = array(
                                            "error" => true,
                                            "response" => $resultResize['content']);
                                    }
                                } else {

                                    // Удаляем запись из БД
                                    $resultDelete = $this->deteteTask($id);
                                    $this->resultOfAddTask = array(
                                        "error" => true,
                                        "response" => $resultDownload['content'] . $resultDelete['content']);
                                }
                            } else {
                                $this->resultOfAddTask = array(
                                    "error" => true,
                                    "response" => "There is no last id!");
                            }
                        } else {
                            $this->resultOfAddTask = array(
                                "error" => true,
                                "response" => $db_result['content']);
                        }
                    } else {
                        $this->resultOfAddTask = array(
                            "error" => true,
                            "response" => "Bad file extention! Use jpg, gif, png");
                    }
                } else {
                    $this->resultOfAddTask = array(
                        "error" => true,
                        "response" => "Bad symbols in task or image link!");
                }
            } else {
                $this->resultOfAddTask = array(
                    "error" => true,
                    "response" => "User not found!");
            }
        /*} else {
            $this->resultOfAddTask = array(
                "error" => true,
                "response" => "Please sign in to add task!");
        }*/
    }

    /*
     * Функция определяет количество страниц и строк в таблице задач
     */
    private function initCountOfRowsAndPages()
    {
        return;
        // Выделяем все данные
        $db_result = DbProvider::query("SELECT * FROM task");
        if (empty($db_result['error'])) {
            $this->countRows = $db_result['content']->num_rows;
        } else {
            $this->countRows = 0;
        }

        // Определяем количество страниц
        $this->pageCount = intval($this->countRows/$this->countRowsOnPage) == $this->countRows/$this->countRowsOnPage
            ? $this->countRows/$this->countRowsOnPage
            : intval($this->countRows/$this->countRowsOnPage) + 1;
    }

    /*
     * @id - id задачи в БД проверяем перед выполнением!
     * Функция удаления задачи из БД
     */
    private function deteteTask($id)
    {
        $db_result = DbProvider::query("DELETE FROM task WHERE id='$id' ");
        return $db_result;
    }

    /*
     * Функция обрабатывает AJAX запрос пользователя
     */
    public function handleAjaxJson2($post)
    {

        // Определяем намерение AJAX
        if ($post['intent'] == "Get user email") {
            if (isset($post['id_user'])) {

                // Инициализируем подключение к БД здесь так как для
                // "checkInjection" нужно действующее соединение в БД
                $dbProvider = new DbProvider();

                // Проверяем поле задачи на SQL-инъекцию
                if ($task = $dbProvider->checkInjection($post['id_user'])) {

                    // Вставляем данный в БД
                    $db_result = $dbProvider->queryThis("SELECT email FROM \"user\" WHERE id = " . $post['id_user']);

                    // Проверяем результат вставки
                    if (empty($db_result['error'])) {
                        $myrow = $db_result['content']->fetch_assoc();
                        return array(
                            "error" => null,
                            "response" => $myrow['email']);
                    } else {
                        return array(
                            "error" => true,
                            "response" => $db_result['content']);
                    }
                } else {
                    return array(
                        "error" => true,
                        "response" => "Bad user id");
                }
            } else {
                return array(
                    "error" => true,
                    "response" => "No user id specified");
            }
        } elseif ($post['intent'] == "Admin change task") {
            // Определяем действия пользователя по изменению страницы или сортировки
            $this->prepareSessionValues();

            // Определяем авторизирован ли пользователь и его уровень
            if ($_SESSION['unregistered'] != 1 && $_SESSION['level'] == 1) {

                // Определяем переданы ли необходимые параметры
                if (isset($post['id_user']) && isset($post['new_task'])) {

                    // Определяем не слишком ли длинная задача
                    if (strlen($post['new_task']) <= $this->taskLenght) {

                        // Инициализируем подключение к БД здесь так как для
                        // "checkInjection" нужно действующее соединение в БД
                        $dbProvider = new DbProvider();

                        // Проверяем поля на SQL-инъекцию
                        $id_user = $dbProvider->checkinjection($post['id_user']);
                        $task = $dbProvider->checkInjection($post['new_task']);
                        if ($id_user && $task) {

                            // Вставляем данные в БД
                            $db_result = $dbProvider->queryThis("UPDATE task SET task = '"
                                . htmlspecialchars($task) . "' WHERE id = " . $id_user);

                            // Проверяем результат вставки
                            if (empty($db_result['error'])) {
                                return array(
                                    "error" => null,
                                    "response" => "Task successfully changed",
                                    "newValue" => $task);
                            } else {
                                return array(
                                    "error" => true,
                                    "response" => $db_result['content']);
                            }
                        } else {
                            return array(
                                "error" => true,
                                "response" => "Bad user id or task");
                        }
                    } else {
                        return array(
                            "error" => true,
                            "response" => "Task lenght must be < 1000 symbols!");
                    }
                } else {
                    return array(
                        "error" => true,
                        "response" => "No user id or task specified");
                }
            } else {
                return array(
                    "error" => true,
                    "response" => "You have no permission to this action!");
            }
        } elseif ($post['intent'] == "Admin change done") {

            // Определяем действия пользователя по изменению страницы или сортировки
            $this->prepareSessionValues();

            // Определяем авторизирован ли пользователь и его уровень
            if ($_SESSION['unregistered'] != 1 && $_SESSION['level'] == 1) {

                // Определяем переданы ли необходимые параметры
                if (isset($post['id_user']) && isset($post['new_done'])) {

                    // Инициализируем подключение к БД здесь так как для
                    // "checkInjection" нужно действующее соединение в БД
                    $dbProvider = new DbProvider();

                    // Проверяем поле юзер_id на SQL-инъекцию и значение выполнения
                    $id_user = $dbProvider->checkInjection($post['id_user']);
                    if ($id_user && ($post['new_done'] == 0 || $post['new_done'] == 1)) {
                        // Вставляем данные в БД
                        $db_result = $dbProvider->queryThis("UPDATE task SET done = '" . $post['new_done']
                            . "' WHERE id = '" . $id_user . "'");

                        // Проверяем результат вставки
                        if (empty($db_result['error'])) {
                            return array(
                                "error" => null,
                                "response" => "Done successfully changed",
                                "newValue" => $post['new_done']);
                        } else {
                            return array(
                                "error" => true,
                                "response" => $db_result['content']);
                        }
                    } else {
                        return array(
                            "error" => true,
                            "response" => "Bad user id or done value");
                    }
                } else {
                    return array(
                        "error" => true,
                        "response" => "No user id or done specified");
                }
            } else {
                return array(
                    "error" => true,
                    "response" => "You have no permission to this action!");
            }
        } else {
            return array(
                "error" => true,
                "response" => "No such intent!");
        }
    }

    /*
     * Функция определения номеров страниц
     */
    private function pagesArrayInit()
    {
        $count = ( 1 + 2 * $this->n ) + 4;

        // Троеточие с обеих сторон
        if ($this->pageCount > $count) {
            if (($this->pageNumber - $this->n - 2) > 1 && ($this->pageNumber + $this->n + 2) < $this->pageCount) {
                $this->pagesArray[0] = 1;
                $this->pagesArray[1] = "...";
                $this->pagesArray[$count - 2] = "...";
                $this->pagesArray[$count - 1] = $this->pageCount;
                for ($i = 2; $i < $count - 2; $i++) {
                    $this->pagesArray[$i] = $this->pageNumber - ($this->n + 2) + $i;
                }
            // Троеточие слева
            } elseif (($this->pageNumber - $this->n - 2) > 0) {
                $this->pagesArray[0] = 1;
                $this->pagesArray[1] = "...";
                $this->pagesArray[$count - 1] = $this->pageCount;
                $centerPage = $this->pageCount - ($this->n + 2);
                for ($i = 2; $i < $count - 1; $i++) {
                    $this->pagesArray[$i] = $centerPage - ($this->n + 2) + $i;
                }
            // Троеточие справа
            } elseif (($this->pageNumber + $this->n + 2) < $this->pageCount) {
                $this->pagesArray[0] = 1;
                $this->pagesArray[$count - 2] = "...";
                $this->pagesArray[$count - 1] = $this->pageCount;
                $centerPage = $this->n + 2 + 1;
                for ($i = 1; $i < $count - 2; $i++) {
                    $this->pagesArray[$i] = $centerPage - ($this->n + 2) + $i;
                }
            }
        } else {

            // Нет троеточия
            for ($i = 1; $i <= $this->pageCount; $i++) {
                $this->pagesArray[$i] = $i;
            }
        }
        ksort($this->pagesArray, SORT_REGULAR);
    }
}