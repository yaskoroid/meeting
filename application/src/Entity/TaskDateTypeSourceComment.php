<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 13.11.2018
 * Time: 12:29
 */

namespace Entity;


class TaskDateTypeSourceComment extends Mapper {
    public $id;
    public $taskTargetDateId;
    public $taskTypeId;
    public $taskSourceId;
    public $comment;
}