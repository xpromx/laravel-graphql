<?php

namespace App\GraphQL\Type;

use App\GraphQL\Support\Definition\Type;
use App\GraphQL\Support\Type as BaseType;
use GraphQL;
use App\Song;

class UserType extends BaseType
{
    protected $attributes = [
        'name' => 'User',
        'description' => 'A type'
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The id of the user'
            ],

            'first_name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => ''
            ],

            'email' => [
                'type' => Type::string(),
                'description' => 'The email of user'
            ],

            'songs' => Type::hasMany('song')

        ];
    }

}
