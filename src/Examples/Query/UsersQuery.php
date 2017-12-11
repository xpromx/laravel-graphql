<?php

namespace App\GraphQL\Query;

use App\GraphQL\Support\Query;
use App\GraphQL\Support\Definition\Type;

class UsersQuery extends Query
{
    protected $attributes = [
        'name' => 'UsersQuery',
        'description' => 'A query',
        'model' => \App\User::class,
    ];

    public function type()
    {
        return Type::connection('user');
    }

}
