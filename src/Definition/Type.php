<?php

namespace Xpromx\GraphQL\Definition;

use GraphQL\Type\Definition\Type as BaseType;
use Xpromx\GraphQL\Type\HasManyType;
use Xpromx\GraphQL\Type\HasOneType;
use Xpromx\GraphQL\Type\ConnectionType;
use GraphQL;

class Type extends BaseType
{
    const META = 'Meta';
    const DATE = 'Date';

    private static $externalTypes;

    public static function type( $type )
    {
        return GraphQL::type( $type );
    }

    public static function hasMany( $name, $method=false )
    {
        $relation = new HasManyType($name, $method);
        return $relation->run();
    }

    public static function hasOne( $name, $method=false, $field=false )
    {
        $relation = new HasOneType($name, $method, $field);
        return $relation->run();
    }

    public static function connection( $name )
    {
        if(!isset(self::$externalTypes[$name]))
        {
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

    private static function getExternalType($name = null)
    {

        if(!isset(self::$externalTypes[$name]))
        {
            // $class = $name.'Type';
            $class = "\App\GraphQL\Support\Type\\{$name}Type";
            self::$externalTypes[$name] = new $class();
        }
        
        return  self::$externalTypes[$name];

    }

}