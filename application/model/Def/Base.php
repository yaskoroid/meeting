<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 31.10.2018
 * Time: 11:56
 */

namespace model\Def;

use core\Service\ServiceLocator;
use Service\Utils;

abstract class Base
{
    public $constPaginationCountOfPagesNearCurrent = 1; // Количество страниц с каждой стороны от текущей (целое больше 1)
    public $constPhoneStart = '+380';
    public $constPhoneNumberLength = 12;

    abstract public function get();

    protected function getRun() {
        $result = array();
        /** @var Utils $utilsService */
        $utilsService = ServiceLocator::utilsService();
        foreach($this as $key => $value) {
            $result['DEF_' . $utilsService->camelCaseToUnderline($key)] = $value;
        }
        return $result;
    }
}