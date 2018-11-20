<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 06.07.2017
 * Time: 16:18
 */

namespace application\vendor\helper;

require_once "application/vendor/helper/Helper.php";
use aService\Basic;
use application\vendor\helper\Helper;

/*
 * Класс предназначенный для скачивания файлов из массива $_FILES
 */
class Downloader extends Basic
{

    // Допустимые MIME-типы для изображений
    const IMG_TYPES = array("image/jpg","image/jpeg","image/gif","image/png");

    private $path; // Путь к файлу
    private $types; // Массив типов
    private $postFieldName; // Имя ключа $_FILES
    private $newName; // Новое имя файла

    /*
     * В конструкторе определяем значения типов,
     * пути, ключа массива $_FILES, нового имени
     */
    function __construct($types, $postFieldName, $newName)
    {
        $this->types = $types;
        $this->postFieldName = $postFieldName;
        $this->newName = $newName;
        $this->path = $_SERVER['DOCUMENT_ROOT'] .
            Downloader::IMG_USERS_FOLDER . "/" . $this->newName;
    }

    /*
     * Функция скачивает
     */
    public function download()
    {
        // Существует ли данный ключ
        if (array_key_exists($this->postFieldName, $_FILES)) {
            // Скачиваем
            copy($_FILES[$this->postFieldName]['tmp_name'], $this->path);

            // Проверяем тип файла
            if (!Helper::checkMime($this->path, $this->types)) {

                // Удаляем файл
                unlink($this->path);
                return array("error" => 1, "content" => "Error! File had bad extention!");
            } else {
                return array("error" => null,
                    "content" => "File successfully download!",
                    "path" => $this->path);
            }
        }
    }
}