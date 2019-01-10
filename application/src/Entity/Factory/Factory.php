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
class Factory {
    /**
     * @var Utils;
     */
    private static $_utilsService;

    /**
     * @param array $fields
     * @param string $entityClassName
     * @param bool|false $isCamelCase
     * @return mixed
     * @throws \Exception
     */
    public static function createEntity(array $fields, $entityClassName, $isCamelCase = false) {
        if (is_null(self::$_utilsService)) {
            self::$_utilsService = ServiceLocator::utilsService();
        }

        $entity = new $entityClassName;
        foreach ($entity as $name=>$value) {
            $keyInFields = $isCamelCase
                ? lcfirst($name)
                : self::$_utilsService->camelCaseToUnderline($name, false);

            if (array_key_exists($keyInFields, $fields)) {
                $entity->$name = $fields[$keyInFields];
            }
        }

        return $entity;
    }
}