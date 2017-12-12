<?php
namespace Xpromx\GraphQL\Query;
use Xpromx\GraphQL\Definition\Type;
use GraphQL;

trait Args {

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

}