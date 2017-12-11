<?php

namespace App\GraphQL\Examples\Query;

use Xpromx\GraphQL\Support\Query;
use Xpromx\GraphQL\Support\Definition\Type;

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
