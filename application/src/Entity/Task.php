<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 13.11.2018
 * Time: 12:19
 */

namespace Entity;


class Task extends Mapper
{
    public $id;
    public $meetingId;
    public $taskNumber;
    public $taskTypeId;
    public $taskSourceId;
    public $lessonId;
    public $userId;
    public $isDone;
    public $isHall;
    public $comment;
}