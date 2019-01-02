<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 07.07.2017
 * Time: 10:58
 */

namespace application\vendor\helper;

use Service\Basic;

class Image extends Basic {

    /**
     * @var int
     */
    private $imageWidth;

    /**
     * @var int
     */
    private $imageHeight;

    /**
     * @var float
     */
    private $ratio;

    function __construct($width, $height) {
        $this->imageWidth = (int) $width;
        $this->imageHeight = (int) $height;
        $this->ratio = $this->imageWidth / $this->imageHeight;
    }

    /**
     * @param string $path
     */
    public function imageResizeProportional($path) {

        if (!file_exists($path))
            throw new \InvalidArgumentException('Image file to resize does not exists');

        list($width, $height) = getimagesize($path);
        $ratio = $width / $height;

        //@TODO Something is not usual
        if ($width > $this->imageWidth || $height > $this->imageHeight) {

            $ratio / $this->ratio >= 1
                ? $this->imageResize($path, $this->imageWidth, $height * ($this->imageWidth / $width), true)
                : $this->imageResize($path, $width * ($this->imageHeight / $height), $this->imageHeight, true);

        }
    }

    /**
     * @param string $file
     * @param int $w
     * @param int $h
     */
    public function imageResize($file, $w, $h) {

        list($width, $height) = getimagesize($file);

        $ext = pathinfo($file, PATHINFO_EXTENSION);
        if ($ext === 'png') {
            $src = imagecreatefrompng($file);
        } elseif ($ext === 'jpg' || $ext === 'jpeg') {
            $src = imagecreatefromjpeg($file);
        } elseif ($ext === 'gif') {
            $src = imagecreatefromgif($file);
        }

        $dst = imagecreatetruecolor($w, $h);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);

        if ($ext === 'png') {
            imagepng($dst, $file);
        } elseif ($ext === 'jpg' || $ext === 'jpeg') {
            imagejpeg($dst, $file);
        } elseif ($ext === 'gif') {
            imagegif($dst, $file);
        }
    }
}