<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 13.11.2018
 * Time: 12:14
 */

namespace Entity;

class Lesson extends Mapper {
    public $id;
    public $textbookId;
    public $number;
    public $name;
    public $isDialog;
    public $isRead;
    public $isSpeach;
}