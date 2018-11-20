<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 05.11.2018
 * Time: 14:19
 */

namespace Entity;

class User extends Mapper
{
    public $id;
    public $name;
    public $surname;
    public $email;
    public $userTypeId;
    public $login;
    public $password;
    public $salt;
    public $isReady;
    public $isReadyOnlyForPartnership;
    public $comment;
    public $sex;
    public $ext;
    public $phone;
    public $customizableSessionValues;
    public $sessionId;
}