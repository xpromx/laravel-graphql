<?php

namespace Xpromx\GraphQL\Type;

use Xpromx\GraphQL\Definition\Type;
use Xpromx\GraphQL\Query;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL;

class hasManyType extends Query
{
    protected $config = [];

    public function __construct($typeName, $modelMethodName=false)
    {
        $method = $modelMethodName;

        if (!$modelMethodName) {
            $method = str_slug($typeName);
        }

        $this->config = [

            'type' => Type::listOf(GraphQL::type($typeName)),
            'relation' => true,
            'args' => $this->args(),
            'resolve' => function ($root, $args, $context, ResolveInfo $info) use ($method, $typeName) {
                
                $relation = $this->getRelationName($root, $method);
                $query = $root->$relation();
                $query = $this->makeQuery( $query, $args, $info );
                return $query->get();

            }

       ];
    }
}
