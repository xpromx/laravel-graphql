<?php

namespace Xpromx\GraphQL\Type;

use Xpromx\GraphQL\Definition\Type;
use Xpromx\GraphQL\Query;
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

                if( !str_contains( get_class( $query ), 'belognsToMany') )
                {
                    return $root->$relation;
                }
                
                $query = $this->builder( $query, $args, $info );

                return $query->get();
            }

       ];

    }

}