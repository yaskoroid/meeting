<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 12.11.2018
 * Time: 11:33
 */

namespace Respect\Data\Styles;


class Meeting extends Standard
{
    public function styledProperty($name)
    {
        return $this->styledName($name);
    }

    public function realName($name)
    {
        $name = $this->camelCaseToSeparator($name, '_');
        return strtolower($name);
    }

    public function realProperty($name)
    {
        return $this->realName($name);
    }

    public function styledName($name)
    {
        return $this->separatorToCamelCase($name, '_');
        //return ucfirst($name);
    }
}