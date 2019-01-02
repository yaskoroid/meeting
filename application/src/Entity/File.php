<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 20.12.2018
 * Time: 16:28
 */

namespace Entity;


class File extends Mapper {
    public $id;
    public $type;
    public $mime;
    public $mimeDescription;
    public $name;
    public $extension;
    public $description;
    public $isTemp;
}