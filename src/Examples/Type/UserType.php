<?php

namespace App\GraphQL\Type;

use Xpromx\GraphQL\Support\Definition\Type;
use Xpromx\GraphQL\Support\Type as BaseType;


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
