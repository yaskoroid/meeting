<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 20.11.2018
 * Time: 12:08
 */

namespace Entity;


class UserChangeConfirm extends Mapper
{
    public $id;
    public $userId;
    public $field;
    public $value;
    public $newValue;
    public $hash;
    public $comment;
    public $dateTimeExpires;
}