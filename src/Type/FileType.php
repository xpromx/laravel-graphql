<?php

namespace Xpromx\GraphQL\Type;

use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;
use GraphQL\Type\Definition\EnumType as EnumObjectType;

class FileType extends ScalarType
{
    public $name = "File";
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
