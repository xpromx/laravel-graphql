<?php

namespace Xpromx\GraphQL\Examples\Type;

use Xpromx\GraphQL\Definition\Type;
use Xpromx\GraphQL\Type as BaseType;


class UserType extends BaseType
{
    protected $attributes = [
        'name' => 'User',
        'description' => 'A type',
        'model' => \App\User::class
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
