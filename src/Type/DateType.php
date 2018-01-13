<?php

namespace Xpromx\GraphQL\Type;

use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;
use Illuminate\Support\Carbon;

class DateType extends ScalarType
{

     public $name = "date";
     public $description = "date field for this model";

     public function __construct()
     {
         Utils::invariant($this->name, 'Type must be named.');
     }

     public function serialize($value)
     {
         return Carbon::parse($value)->format('M j, Y - H:ia');
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