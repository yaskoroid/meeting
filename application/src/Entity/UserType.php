<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 05.11.2018
 * Time: 19:02
 */

namespace Entity;

class UserType extends Mapper {
    public $id;
    public $role;
    public $description;

    public $permissionCreateSelfUser;
    public $permissionUpdateSelfUser;
    public $permissionDeleteSelfUser;
    public $permissionReadSelfUser;

    public $permissionCreateCustomer;
    public $permissionUpdateCustomer;
    public $permissionDeleteCustomer;
    public $permissionReadCustomer;

    public $permissionCreateAdministrator;
    public $permissionUpdateAdministrator;
    public $permissionDeleteAdministrator;
    public $permissionReadAdministrator;

    public $permissionCreateMeeting;
    public $permissionUpdateMeeting;
    public $permissionDeleteMeeting;
    public $permissionReadMeeting;

    public $permissionCreateSelfTask;
    public $permissionUpdateSelfTask;
    public $permissionDeleteSelfTask;
    public $permissionReadSelfTask;

    public $permissionCreateTask;
    public $permissionUpdateTask;
    public $permissionDeleteTask;
    public $permissionReadTask;

    public $permissionCreateTaskSource;
    public $permissionUpdateTaskSource;
    public $permissionDeleteTaskSource;
    public $permissionReadTaskSource;

    public $permissionCreateTaskTargetDate;
    public $permissionUpdateTaskTargetDate;
    public $permissionDeleteTaskTargetDate;
    public $permissionReadTaskTargetDate;

    public $permissionCreateTaskType;
    public $permissionUpdateTaskType;
    public $permissionDeleteTaskType;
    public $permissionReadTaskType;

    public $permissionCreateTaskDateTypeSourceComment;
    public $permissionUpdateTaskDateTypeSourceComment;
    public $permissionDeleteTaskDateTypeSourceComment;
    public $permissionReadTaskDateTypeSourceComment;

    public $permissionCreateTextbook;
    public $permissionUpdateTextbook;
    public $permissionDeleteTextbook;
    public $permissionReadTextbook;

    public $permissionCreateLesson;
    public $permissionUpdateLesson;
    public $permissionDeleteLesson;
    public $permissionReadLesson;
}
