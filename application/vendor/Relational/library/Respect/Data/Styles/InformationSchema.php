<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 12.11.2018
 * Time: 14:17
 */

namespace Respect\Data\Styles;


class InformationSchema extends Standard
{
    public function styledProperty($name)
    {
        return $this->styledName($name);
    }

    public function realName($name)
    {
        $name = $this->camelCaseToSeparator($name, '_');
        return strtoupper($name);
    }

    public function realProperty($name)
    {
        return $this->realName($name);
    }

    public function styledName($name)
    {
        $separator = preg_quote('_', '/');
        $names = explode($separator, $name);
        $names = array_map(
            function($name) {
                return ucfirst(strtolower($name));
            },
            $names);
        return lcfirst(implode('', $names));
    }
}