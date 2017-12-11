<?php

namespace Xpromx\GraphQL\Examples\Query;

use Xpromx\GraphQL\Query;
use Xpromx\GraphQL\Definition\Type;

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
