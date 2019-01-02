<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 06.11.2018
 * Time: 14:41
 */

namespace core\Service;

use Service;

class ServiceFactory {

    /**
     * @var array
     */
    public static $services = [];

    /**
     * @param string $serviceClassName
     * @return Service\Basic
     */
    public function get($serviceClassName) {
        $serviceClassName = trim($serviceClassName, "\\");
        return self::_instance($serviceClassName);
    }

    /**
     * @param string $serviceClassName
     * @return Service\Basic
     */
    private static function _instance($serviceClassName) {

        $contextServiceName = "Service\\Context";
        if (array_key_exists($serviceClassName, self::$services) && $serviceClassName === $contextServiceName)
            return self::$services[$serviceClassName];

        /* @var $contextService Service\Context */
        if ($serviceClassName === $contextServiceName) {
            $contextService = new $serviceClassName();
            self::$services[$serviceClassName] = $contextService;
            return $contextService;
        } else
            $contextService = self::_instance($contextServiceName);


        $isSingleton = forward_static_call([$serviceClassName, 'isSingleton']);
        if ($isSingleton)
            return forward_static_call([$serviceClassName, 'instanceSingleton']);

        if (array_key_exists($serviceClassName.$contextService->hash(), self::$services) &&
            $serviceClassName !== $contextServiceName)
            return self::$services[$serviceClassName.$contextService->hash()];


        $service = new $serviceClassName();
        self::$services[$serviceClassName.$contextService->hash()] = $service;
        return $service;
    }
}