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

    /**
     * @var Service\Path
     */
    private $_pathService;

    public $constImagePath = '';

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
        $this->_pathService     = ServiceLocator::pathService();
        $this->constUsersTypes  = $this->_userTypeService->getUsersTypesSecure();
        $this->_init();
    }

    public function get() {
        return parent::_getRun();
    }

    private function _init() {
        $this->constImagePath = $this->_pathService->adapterFromHttpAccess(
            $this->_pathService->getFileTypePath('image')
        );
    }
}