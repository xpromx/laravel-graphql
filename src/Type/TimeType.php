<?php

namespace Xpromx\GraphQL\Type;

use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;
use Illuminate\Support\Carbon;

class TimeType extends ScalarType
{
    public $name = "time";
    public $description = "time field for this model";

    public function __construct()
    {
        Utils::invariant($this->name, 'Type must be named.');
    }

    public function serialize($value)
    {
        return Carbon::parse($value)->format('H:i');
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
