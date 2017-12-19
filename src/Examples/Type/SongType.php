<?php

namespace Xpromx\GraphQL\Examples\Type;

use Xpromx\GraphQL\Definition\Type;
use Xpromx\GraphQL\Type as BaseType;

class SongType extends BaseType
{
    protected $attributes = [
        'name' => 'SongType',
        'description' => 'A type',
        'model' => \App\User::class,
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
