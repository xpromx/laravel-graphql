<?php

namespace Xpromx\GraphQL\Support;
use GraphQL\Type\Definition\ResolveInfo;
use Folklore\GraphQL\Support\Query as BaseQuery;
use Xpromx\GraphQL\Support\Definition\Type;

class Query extends BaseQuery
{

    public function builder( $query, $args, $info )
    {

        $query = $this->makeRelations( $query, $info );

        if( isset( $args['hasRelation']) )
        {
            $query->has( $args['hasRelation'] );
        }

        if( isset( $args['doesntHaveRelation']) )
        {
            $query->doesntHave( $args['doesntHaveRelation'] );
        }

        if( isset( $args['orderBy']) )
        {
            $query->orderBy( $args['orderBy'] );
        }

        if( isset( $args['limit']) && isset( $args['page'] ) )
        {
            $query = $query->paginate($args['limit'], ['*'], 'page', $args['page']);
        }

        if( isset( $args['limit']) && !isset( $args['page'] ) )
        {
            $query->take( $args['limit'] );
        }

        return $query;

    }

    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        $query = app()->make( $this->attributes['model'])->query();
        $query = $this->builder( $query, $args, $info );

        if( str_contains( get_class($query), 'Paginator') )
        {
            return $query;
        }

        return $query->get();
    }


    public function makeRelations( $query, $info )
    {
        $fields = $info->getFieldSelection(2); // get fields from graphql query
        $selectFields = []; // add fields for main query
        
        if( key($fields) == 'nodes' )
        {
            $fields = $fields['nodes'];
        }

        foreach( $fields as $field=>$value )
        {
            // check if the field is a relation or not
            if( method_exists( $query->getModel(), $field ) )
            {
                $relation = $query->getModel()->$field(); // get that relation

                if( !str_contains( get_class( $relation ), 'HasMany') )
                {
                    $selectFields[] = $relation->getForeignKey();
                }  

                $relation_fields = array_keys($value); // get the fields of the relation

                foreach( $relation_fields as $key=>$subField )
                {
                    // check one more time if that fields are no relations
                    if( method_exists( $relation->getModel(), $subField ) )
                    {
                        unset($relation_fields[$key]); 
                    }
                }
                
                // make the relations with the fields
                $relation_fields = implode(',', $relation_fields);
                $query->with( $field . ':' . $relation_fields);

            }
            else
            {
                $selectFields[] = $field;
            }
            
        }

        $query->select( $selectFields );

        return $query;
    }

    public function getRelationName( $root, $method )
    {
        $relation = false;

        // check method
        if( method_exists( $root, $method ) )
        {
            $relation = $method;
        }
        
        // plural method
        if( !$relation && method_exists( $root, str_plural($method) ) )
        {
            $relation = str_plural($method);
        }   
        
        // singular method
        if( !$relation && method_exists( $root, str_singular($method) ) )
        {
            $relation = str_singular($method);
        }

        return $relation;
    }


    public function run()
    {
        return $this->config;
    }

    public function type()
    {
        if( isset( $this->config['type'] ) )
        {
            return $this->config['type'];
        }

        return false;
    }

    public function args()
    {
        $args = [
            
            'limit' => [
                'name' => 'limit',
                'type' => Type::Int(),
                'description' => 'Returns limited results.',
            ],

            'page' => [
                'name' => 'page',
                'type' => Type::Int(),
                'description' => 'number of page per results.',
            ],

            'hasRelation' => [
                'name' => 'hasRelation',
                'type' => Type::string(),
                'description' => 'Filter results that has a relation.',
            ],

            'doesntHaveRelation' => [
                'name' => 'doesntHaveRelation',
                'type' => Type::string(),
                'description' => 'Filter results that doesnt have a relation.',
            ],

            'orderBy' => [
                'name' => 'orderBy',
                'type' => Type::string(),
                'description' => 'Returns the elements order by a specific field.',
            ],

        ];

        return $args;              
    }

}