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

abstract class Base {

    abstract public function get();

    protected function _getRun() {
        $result = array();
        /** @var Utils $utilsService */
        $utilsService = ServiceLocator::utilsService();
        foreach($this as $key => $value) {
            $result['DEF_' . $utilsService->camelCaseToUnderline($key)] = $value;
        }

        $classReflection = new \ReflectionClass(get_called_class());
        $staticProperties = $classReflection->getStaticProperties();
        foreach ($staticProperties as $propertyName => $value) {
            $result['DEF_' . $utilsService->camelCaseToUnderline($propertyName)] = $value;
        }
        return $result;
    }

    /**
     * @param $className
     * @return mixed
     */
    private function _getStaticPropertiesOfClassIerarhie($className) {
        $classReflection = new \ReflectionClass($className);
        $staticProperties = $classReflection->getStaticProperties();

        $parentClassReflection = $this->_getParentClassOfClass($classReflection);
        while($parentClassReflection) {
            array_merge($staticProperties, $this->_getStaticPropertiesOfClassReflection($parentClassReflection));
            $parentClassReflection = $this->_getParentClassOfClass($classReflection);
        }
    }

    /**
     * @param \ReflectionClass $classReflection
     * @return array
     */
    private function _getStaticPropertiesOfClassReflection($classReflection) {
        return $classReflection->getStaticProperties();
    }

    /**
     * @param $className
     * @return \ReflectionClass|false
     */
    private function _getParentClassOfClass($className) {
        $class = new \ReflectionClass($className);
        return $class->getParentClass();
    }

}