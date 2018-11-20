<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 05.11.2018
 * Time: 19:02
 */

namespace Entity;

class UserType extends Mapper
{
    public $id;
    public $role;
    public $description;
    public $permissionForUserCreateSelf;
    public $permissionForUserUpdateSelf;
    public $permissionForUserDeleteSelf;
    public $permissionForUserReadSelf;
    public $permissionForUserCreateCustomer;
    public $permissionForUserUpdateCustomer;
    public $permissionForUserDeleteCustomer;
    public $permissionForUserReadCustomer;
    public $permissionForUserCreateAdministrator;
    public $permissionForUserUpdateAdministrator;
    public $permissionForUserDeleteAdministrator;
    public $permissionForUserReadAdministrator;
}
