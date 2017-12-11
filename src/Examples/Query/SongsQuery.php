<?php

namespace Xpromx\GraphQL\Examples\Query;

use Xpromx\GraphQL\Query;
use Xpromx\GraphQL\Definition\Type;

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
