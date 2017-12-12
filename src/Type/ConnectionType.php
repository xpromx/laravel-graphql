<?php

namespace Xpromx\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use Xpromx\GraphQL\Definition\Type;
use GraphQL;

class ConnectionType extends ObjectType {

    public $type = false;

    public function __construct( $typeName )
    {
        $this->type = $typeName;
        
        $config = [

            'name'  => str_plural( $typeName ) . 'Connection',
            'model' => GraphQL::type($typeName)->config['model'],

            'fields' => [

                'nodes' => [
                    'type' => Type::listOf( GraphQL::type($typeName) ),
                    'resolve' => function( $root ){
                        
                        return $root;

                    },
                ],

                'pageInfo' => [
                    'type' => GraphQL::type('PageInfo'),
                    'resolve' => function( $root, $args, $context ){

                        $pagination = $root->toArray();

                        if( $pagination['next_page_url'] )
                        {
                            $pagination['next_page'] = $pagination['current_page'] + 1;
                        }

                        if( $pagination['prev_page_url'] )
                        {
                            $pagination['prev_page'] = $pagination['current_page'] - 1;
                        }

                        return $pagination;
                    }
                ],
                
                
            ]
        ];

        parent::__construct($config);

    }

}