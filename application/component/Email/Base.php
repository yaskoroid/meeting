<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 20.11.2018
 * Time: 11:59
 */

namespace component\Email;

include('template.php');

$bgcolor = '#CCFFCC';
$text = 'Тест класса!';
$title = 'ТЕСТ!';
$tpl = new template('./data','.tpl'); # создали объект, задали каталог и расширение
$tpl -> load('body'); # зашрузили шаблон
$tpl -> vars('body',array('text','title','bgcolor')); # указали какие переменные преобразовать, они должны быть заданы зарание
echo $tpl -> out('body'); # вывели шаблон

class Base
{

}

class template {

    protected $data = array();
    protected $root = '.';
    protected $ext = '.tpl';
    protected $da_vr = array();

    function template($dir, $ext) {
        if (is_dir($dir)) {
            $this->root = $dir;
        } else {
            throw new \InvalidArgumentException("Bad template directory");
        }
        $this->ext = $ext;
    }

    function load($name) {
        $nn = $name;
        $dir = $this->root;
        $ext = $this->ext;
        $name = $dir.'/'.$name.$ext;
        if(!is_file($name)) {die('Ошибка <b>'.$name.'</b> - это не файл!');}
        $fp = fopen($name,'r');
        $data = fread($fp,filesize($name));
        fclose($fp);
        $this -> data[$nn] = $data;
        $this -> da_vr[$nm] = $data;
    }

    function vars($nm, $vars = array()) {
        $data = $this->data[$nm];
        while(list($id, $var) = each($vars)){

            global $$vars[$id];
            $data=str_replace('{'.$vars[$id].'}',$$vars[$id],$data);
        }
        $this -> da_vr[$nm] = $data;
    }

    function out($name) {
        $ret = $this->da_vr[$name];
        $this->da_vr[$name] = $this->data[$name];
        return $ret;
    }
}