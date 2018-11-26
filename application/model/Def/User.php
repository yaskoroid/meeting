<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 31.10.2018
 * Time: 11:58
 */

namespace model\Def;

/**
 * Эти значения будут переданы как глобальные переменные объекта
 * window. Ко всем будет добавлено слово 'DEF_'.
 * Если значение начинается с const - то оно не попадет в куки,
 * а значит будет всегда вибираться только это значение
 */
class User extends Def
{
    public $usersCountOnPage = 3;
    public $constUsersCountOnPageValues = '1,3,5,10,20,50,100,500';
    public $sortingDirection = 'asc';
    public $pageNumber = 1;
    public $constImageUserPath = "/images/users/user_";
    public $constUserCommentLength = 500;
    public $constUserImageWidth = 320;
    public $constUserImageHeight = 240;

    public function get() {
        return parent::getRun();
    }
}