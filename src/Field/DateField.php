<?php

namespace Xpromx\GraphQL\Field;

use Illuminate\Support\Carbon;
use Xpromx\GraphQL\Definition\Type;

trait DateField
{

    public static function dateField($field='created_at', $format='M j, Y')
    {
        return [
            'type' => Type::string(),
            'args' => [
                'format' => [
                    'type' => Type::string(),
                    'defaultValue' => $format
                ],
            ],
            'resolve' => function ($root, $args, $context) use($field) {
                return Carbon::parse($root->$field)->format($args['format']);
            }
        ];
    }

}