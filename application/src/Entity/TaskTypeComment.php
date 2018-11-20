<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 13.11.2018
 * Time: 12:29
 */

namespace application\src\Entity;


class TaskTypeComment extends Mapper
{
    public $id;
    public $taskTypeId;
    public $taskTargetDateId;
    public $comment;
}