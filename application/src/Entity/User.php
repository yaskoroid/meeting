<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 05.11.2018
 * Time: 14:19
 */

namespace Entity;

class User extends Mapper {
    public $id;
    public $login;
    public $email;
    public $userTypeId;
    public $name;
    public $surname;
    public $phone;
    public $sex;
    public $isReady;
    public $isReadyOnlyForPartnership;
    public $image;
    public $imageExt;
    public $comment;
    public $salt;
    public $password;
    public $customizableSessionValues;
    public $sessionId;
}