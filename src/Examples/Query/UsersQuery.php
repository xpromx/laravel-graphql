<?php

namespace Xpromx\GraphQL\Examples\Query;

use Xpromx\GraphQL\Query;
use Xpromx\GraphQL\Definition\Type;

class UsersQuery extends Query
{
    protected $attributes = [
        'name' => 'UsersQuery',
        'description' => 'A query',
    ];

    public function type()
    {
        return Type::connection('user');
    }

}
