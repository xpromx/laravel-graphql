<?php
namespace Xpromx\GraphQL\Filter;

use Folklore\GraphQL\Support\Type as GraphQLType;

class FilterConditionEnum extends GraphQLType
{
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'Filter Condition',
        'description' => 'List of filter that can be applied',
        'values' => [
            'GT'  => 'GT',
            'GTE' => 'GTE',
            'LT'  => 'LT',
            'LTE' => 'LTE',
            'EQUAL'  => 'EQUAL',
            'CONTAINS' => 'CONTAINS',
            'NOT_CONTAINS' => 'NOT_CONTAINS',
            'STARTS_WITH' => 'STARTS_WITH',
            'ENDS_WITH'  => 'ENDS_WTIH',
            'IN' => 'IN',
            'NOT_IN' => 'NOT_IN',
            'NOT_EQUAL' => 'NOT_EQUAL',
            'NULL' => 'NULL',
            'NOT_NULL' => 'NOT_NULL'
        ],
    ];
}
