<?php

namespace App\GraphQL\Support\Type;

use App\GraphQL\Support\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;

class PageInfoType extends BaseType
{
    protected $attributes = [
        'name' => 'PageInfo',
    ];

    public function fields()
    {
        return [

            'current_page' => [
                'type' => Type::Int(),
            ],

            'next_page' => [
                'type' => Type::Int(),
            ],

            'prev_page' => [
                'type' => Type::Int(),
            ],

            'last_page' => [
                'type' => Type::Int(),
            ],

            'per_page' => [
                'type' => Type::Int(),
            ],

            'total' => [
                'type' => Type::Int(),
            ],

        ];
    }

}
