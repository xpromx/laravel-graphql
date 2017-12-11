<?php

namespace App\GraphQL\Query;

use App\GraphQL\Support\Query;
use App\GraphQL\Support\Definition\Type;

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
