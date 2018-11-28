<?php
namespace core\Model;

abstract class Base {
    /**
     * @return array
     */
    abstract public function getData();

    /**
     * @param array $post
     * @return array
     */
    abstract public function handleAjaxJson(array $post);
}