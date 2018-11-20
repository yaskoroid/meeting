<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 12.11.2018
 * Time: 13:34
 */

namespace Entity\InformationSchema;

use Entity\Mapper;

class Columns extends Mapper
{
    public $tableCatalog;
    public $tableSchema;
    public $tableName;
    public $columnName;
    public $ordinalPosition;
    public $columnDefault;
    public $isNullable;
    public $dataType;
    public $characterMaximumLength;
    public $characterOctetLength;
    public $numericPrecision;
    public $numericScale;
    public $datetimePrecision;
    public $characterSetName;
    public $collationName;
    public $columnType;
    public $columnKey;
    public $extra;
    public $privileges;
    public $columnComment;
}