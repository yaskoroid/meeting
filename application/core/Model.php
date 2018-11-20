<?php
namespace core;

/*
 * Базовый класс модели, содержит метод getData()
 */
abstract class Model
{
    // Метод получения данных (обязательный в модели)
    abstract public function getData();
}