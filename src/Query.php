<?php

namespace Xpromx\GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use Folklore\GraphQL\Support\Query as BaseQuery;
use Xpromx\GraphQL\Definition\Type;
use Xpromx\GraphQL\Filter\FilterQuery;
use GraphQL;

class Query extends BaseQuery
{
    use FilterQuery;

    public $single = false;

    public function builder( $query, $args, $info )
    {

        $query = $this->makeRelations( $query, $info );

        // Filters
        if( isset( $args['id']) )
        {
            $query->where( 'id',  $args['id'] );
        }

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
            $orderBy = explode(' ', $args['orderBy']);
            $query->orderBy( $orderBy[0], $orderBy[1] ?? 'ASC' );
        }

        if( isset( $args['filter']) )
        {
            $query = $this->applyFilters( $query, $args['filter'] );
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
        $query = app()->make( $info->returnType->config['model'] )->query();

        $query = $this->builder( $query, $args, $info );
        
        if( str_contains( get_class($query), 'Paginator') )
        {
            return $query;
        }

        if( $this->single )
        {
            return $query->first();
        }

        return $query->get();
    }


    public function makeRelations( $query, $info )
    {
        
        $type = $this->getType( $info->returnType );
        $fields = $info->getFieldSelection(2); // get fields from graphql query
        if( key($fields) == 'nodes' ){ $fields = $fields['nodes']; }

        $queryFields = $this->getFields( $type, $fields );
        $queryRelations = $this->getRelations( $type, $fields ); // relations in this query
       
        if( count($queryRelations) > 0 )
        {
            foreach( $queryRelations as $field=>$type )
            {
                $relation = $query->getModel()->$field();

                $queryRelationFields = $this->getFields( $type, $fields[ $field ] );
                
                if( !str_contains( get_class( $relation ), 'many') )
                {
                    if( method_exists($relation, 'getForeignKey') )
                    {
                        $queryFields[] = $relation->getForeignKey();
                    }
                }



                $query->with( $field . ':' . implode(',', $queryRelationFields) );

            }
        }

        $query->select( $queryFields );
        
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

    public function singleArgs()
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull( Type::Int() ),
                'description' => 'Find by ID'
            ]
        ];
    }

    public function connectionArgs()
    {
        return [
            
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

            'filter' => [
                'name' => 'filter',
                'type' => Type::listOf( GraphQL::type('Filter') ), 
                'description' => 'Apply fields filters'
            ],

        ];
    }

    public function args()
    {
        $args = [];
        $this->single = false;

        $current = get_class( $this );
        $type = ( is_object( $this->type() ) ? get_class( $this->type() ) : false );        

        if(  str_contains( $current , 'Query') )
        {
            $args = $this->singleArgs();
            $this->single = true;
        }

        if( str_contains( $type , 'Connection') || str_contains($current, 'hasMany') )
        {
            $this->single = false;
            $args = $this->connectionArgs();
        }

        return $args;              
    }

    public function getFields( $type, $queryFields )
    {
        $type = GraphQL::type( $type ); 
        $table = app()->make($type->config['model'])->getTable();

        // get fields names
        foreach( $type->getFields() as $field )
        {
            if( isset($field->config['field']) || ( !isset($field->config['relation']) && !isset($field->config['ignore']) ) )
            {
                $key = ( isset($field->config['field']) ? $field->config['field'] : $field->name );
                $fields[ $field->name ] = $key;                
            }
        }

        $selectedFields['id'] = $table .'.id';

        // only keep the ones we selected
        foreach( $queryFields as $key=>$field )
        {
            if( isset( $fields[ $key ] ) )
            {
                $selectedFields[] = $table . '.' . $fields[ $key ];
            }
        }

       return $selectedFields;
       
    }

    public function getRelations( $type, $queryFields )
    {
        
        $type = GraphQL::type( $type ); 

        $relations = [];

        foreach( $type->getFields() as $field )
        {
            if( isset($field->config['relation']) && isset( $queryFields[$field->name] ) )
            {         
                $relations[$field->name] = $this->getType( $field->getType() );
            }
        }

        return $relations;
    }

    public function getType( $object )
    {

        if( property_exists($object, 'type' ) )
        {
            return $object->type;
        }

        if( !property_exists($object, 'config') )
        {
            return $object->ofType->config['name'];
        }

        return $object->config['name'];
    }

}