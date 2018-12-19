<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 07.07.2017
 * Time: 10:58
 */

namespace application\vendor\helper;

use Service\Basic;

class Image extends Basic
{

    private $imageWidth; // Ширина изображения (требуемая)
    private $imageHeight; // Высота изображения (требуемая)
    private $r; // Соотношение сторон (требуемое)

    /*
     * В конструкторе инициализируем значения ширины,
     * высоты и соотношения сторон
     */
    function __construct($iWidth, $iheight) {
        $this->imageWidth = (int) $iWidth;
        $this->imageHeight = (int) $iheight;
        $this->r = $this->imageWidth / $this->imageHeight;
    }

    /*
     * Функция пропорционального изменения размеров
     * изображения не более заданных
     */
    public function imageResizeProportional($path) {
        try {
            // Получаем значения ширины, высоты и соотношения
            // сторон исходного изображения
            list($width, $height) = getimagesize($path);
            $r = $width / $height;

            // Определяем нужно ли вообще изменять размер
            if ($width > $this->imageWidth || $height > $this->imageHeight) {

                // Изображение более вытянутое или сплюснутое
                $rr = $r / $this->r;
                if ($rr >= 1) {
                    Image::imageResize($path, $this->imageWidth, $height * ($this->imageWidth / $width), true);
                } else {
                    Image::imageResize($path, $width * ($this->imageHeight / $height), $this->imageHeight, true);
                }
            }
            return array("error" => null, "content" => "Image has been successfully resized");
        } catch (\Exception $e) {
            return array("error" => 1, "content" => "An error occurred when image has been trying resize!");
        }
    }

    /*
     * Функция изменения размеров изображения под требуемые
     */
    public static function imageResize($file, $w, $h) {

        // Получаем значения ширины, высоты и соотношения
        // сторон исходного изображения
        list($width, $height) = getimagesize($file);

        // Определяем тип файла по расширению
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        if ($ext == "png") {
            $src = imagecreatefrompng($file);
        } elseif ($ext == "jpg" || $ext == "jpeg") {
            $src = imagecreatefromjpeg($file);
        } elseif ($ext == "gif") {
            $src = imagecreatefromgif($file);
        }

        // Создаем новое изображение
        $dst = imagecreatetruecolor($w, $h);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);

        // Сохраним файл изображения
        if ($ext == "png") {
            imagepng($dst, $file);
        } elseif ($ext == "jpg" || $ext == "jpeg") {
            imagejpeg($dst, $file);
        } elseif ($ext == "gif") {
            imagegif($dst, $file);
        }
    }
}