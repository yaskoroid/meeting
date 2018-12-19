<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 20.11.2018
 * Time: 12:08
 */

namespace Entity;


class ChangeConfirm extends Mapper {
    public $id;
    public $entityName;
    public $entityId;
    public $type;
    public $field;
    public $value;
    public $newValue;
    public $hash;
    public $comment;
    public $dateTimeExpires;
}