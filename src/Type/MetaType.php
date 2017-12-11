<?php

namespace App\GraphQL\Support\Type;

use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;
use GraphQL\Type\Definition\EnumType as EnumObjectType;

class MetaType extends ScalarType
{

     public $name = "meta";
     public $description = "meta field for this model";

     public function __construct()
     {
         Utils::invariant($this->name, 'Type must be named.');
     }

     public function serialize($value)
     {
         return $value;
     }

    public function parseValue($value)
    {
        return $value;
    }

    public function parseLiteral($value)
    {
        return $value;
    }


}