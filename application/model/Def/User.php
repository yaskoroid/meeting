<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 31.10.2018
 * Time: 11:58
 */

namespace model\Def;

use Service;
use core\Service\ServiceLocator;

/**
 * Эти значения будут переданы как глобальные переменные объекта
 * window. Ко всем будет добавлено слово 'DEF_'.
 * Если значение начинается с const - то оно не попадет в куки,
 * а значит будет всегда вибираться только это значение
 */
class User extends Def {

    /**
     * @var Service\User\Type
     */
    private $_userTypeService;

    public static $constImageUserPath          = '/images/user';
    public static $constImageUserTempPath      = '/images/user/temp';

    public $usersCountOnPage           = 3;
    public $constUserCountOnPageValues = '1,3,5,10,20,50,100,500';
    public $userSorting                = 'id';
    public $sortingDirection           = 'asc';
    public $userSearchText             = '';
    public $pageNumber                 = 1;
    public $constUserCommentLength     = 500;
    public $constUserImageWidth        = 320;
    public $constUserImageHeight       = 240;
    public $constUsersTypes            = array();

    function __construct() {
        $this->_userTypeService = ServiceLocator::userTypeService();
        $this->constUsersTypes  = $this->_userTypeService->getUsersTypesSecure();
    }

    public function get() {
        return parent::_getRun();
    }
}