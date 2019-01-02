<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 20.11.2018
 * Time: 12:16
 */

namespace Entity;


class Email {
    public $id;
    public $emailFromUserId;
    public $emailToUserId;
    public $type;
    public $title;
    public $body;
    public $css;
    public $comment;
    public $dateTime;
    public $isAccepted;
}