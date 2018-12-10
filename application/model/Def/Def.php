<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 26.11.2018
 * Time: 16:18
 */

namespace model\Def;


class Def extends Base {

    public static $constPhoneNumberLength = 12;

    public $constPaginationCountOfPagesNearCurrent = 1; // Количество страниц с каждой стороны от текущей (целое больше 1)
    public $constPhoneStart = '+380';

    public function get() {
        return parent::_getRun();
    }
}