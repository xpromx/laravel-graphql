<?php

namespace Xpromx\GraphQL\Filter;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

class FilterType extends BaseType
{
    protected $attributes = [
        'name' => 'Filter',
        'description' => 'A Filter',
        'ignore' => true,
    ];

    protected $inputObject = true;

    public function fields()
    {
        return [
            
            'field' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'name of field to apply filter',
            ],

            'condition' => [
                'type' => Type::nonNull(GraphQL::type('FilterCondition')),
                'description' => 'filtes allowed',
            ],

            'value' => [
                'type' => Type::string(),
                'description' => 'value to filter, separate multiple values with ","',
            ],

            'relation' => [
                'type' => Type::string(),
                'description' => 'apply this filter to one relation',
            ],

        ];
    }
}
