<?php

namespace Xpromx\GraphQL\Definition;

use GraphQL\Type\Definition\Type as BaseType;

// Types
use Xpromx\GraphQL\Type\HasManyType;
use Xpromx\GraphQL\Type\HasOneType;
use Xpromx\GraphQL\Type\ConnectionType;
use Xpromx\GraphQL\Type\MetaType;
use Xpromx\GraphQL\Type\DateType;
use Xpromx\GraphQL\Type\TimeType;
use Xpromx\GraphQL\Type\FileType;

// Fields
use Xpromx\GraphQL\Field\DateField;
use Xpromx\GraphQL\Field\TimeField;

use GraphQL;

class Type extends BaseType
{
    use DateField;
    use TimeField;
    
    const META = 'Meta';
    const DATE = 'Date';
    const TIME = 'Time';
    const FILE = 'File';

    private static $externalTypes;

    public static function type($type)
    {
        return GraphQL::type($type);
    }

    public static function is($type, $params=[])
    {
        return [ 'type' => self::$type() ] + $params;
    }

    public static function hasMany($name, $method=false)
    {
        $relation = new HasManyType($name, $method);
        return $relation->run();
    }

    public static function hasOne($name, $method=false, $field=false)
    {
        $relation = new HasOneType($name, $method, $field);
        return $relation->run();
    }

    public static function connection($name)
    {
        if (!isset(self::$externalTypes[$name])) {
            self::$externalTypes[$name] = new ConnectionType($name);
        }

        return  self::$externalTypes[$name];
    }

    public static function meta()
    {
        return self::getExternalType(self::META);
    }

    public static function date()
    {
        return self::getExternalType(self::DATE);
    }

    public static function time()
    {
        return self::getExternalType(self::TIME);
    }

    public static function file()
    {
        return self::getExternalType(self::FILE);
    }

    private static function getExternalType($name = null)
    {
        if (!isset(self::$externalTypes[$name])) {
            // $class = $name.'Type';
            $class = "\Xpromx\GraphQL\Type\\{$name}Type";
            self::$externalTypes[$name] = new $class();
        }
        
        return  self::$externalTypes[$name];
    }
}
