<?php

namespace App\GraphQL\Examples\Query;

use Xpromx\GraphQL\Support\Query;
use Xpromx\GraphQL\Support\Definition\Type;

class SongsQuery extends Query
{
    protected $attributes = [
        'name' => 'SongsQuery',
        'description' => 'A query',
        'model' => \App\Song::class
    ];

    public function type()
    {
        return Type::connection('song');
    }

}
