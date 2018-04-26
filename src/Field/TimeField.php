<?php

namespace Xpromx\GraphQL\Field;

use Illuminate\Support\Carbon;
use Xpromx\GraphQL\Definition\Type;

trait TimeField
{

    public static function timeField($field='created_at', $format='H:i')
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