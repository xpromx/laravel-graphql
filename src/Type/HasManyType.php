<?php

namespace Xpromx\GraphQL\Support\Type;

use Xpromx\GraphQL\Support\Definition\Type;
use Xpromx\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL;

class hasManyType extends Query {

    protected $config = [];

    public function __construct( $typeName, $method=false )
    {
        
       if( !$method )
       {
            $method = str_slug($typeName);
       }

       $this->config = [

            'type' => Type::listOf( GraphQL::type($typeName) ),
            'relation' => true,
            'args' => $this->args(),
            'resolve' => function( $root, $args, $context, ResolveInfo $info ) use ($method, $typeName)
            {
                $relation = $this->getRelationName($root, $method);
                $query = $root->$relation();
                
                $query = $this->builder( $query, $args, $info );

                return $query->get();
            }

       ];

    }

}