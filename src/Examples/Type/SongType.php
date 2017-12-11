<?php

namespace App\GraphQL\Type;

use App\GraphQL\Support\Definition\Type;
use App\GraphQL\Support\Type as BaseType;

class SongType extends BaseType
{
    protected $attributes = [
        'name' => 'SongType',
        'description' => 'A type'
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The id of the user'
            ],

            'user' => Type::hasOne('user'),

            'title' => [
                'type' => Type::string(),
                'description' => ''
            ],

            'picture' => [
                'type' => Type::string(),
                'description' => ''
            ]
        ];
    }
}
