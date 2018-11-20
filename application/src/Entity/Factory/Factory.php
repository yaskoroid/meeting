<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 13.11.2018
 * Time: 17:27
 */

namespace Entity\Factory;

use core\Service\ServiceLocator;
use Service\Utils;
class Factory
{
    /**
     * @var Utils;
     */
    private static $_utilsService;

    public static function createEntity(array $fields, $entityClassName) {
        if (is_null(self::$_utilsService)) {
            self::$_utilsService = ServiceLocator::utilsService();
        }

        $entity = new $entityClassName;
        foreach ($entity as $name=>$value) {
            $keyInFields = self::$_utilsService->camelCaseToUnderline($name, false);
            if (array_key_exists($keyInFields, $fields)) {
                $entity->$name = $fields[$keyInFields];
            }
        }

        return $entity;
    }
}