<?php

namespace Xpromx\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use Xpromx\GraphQL\Definition\Type;
use GraphQL;

class ConnectionType extends ObjectType
{
    public $type = false;

    public function __construct($typeName)
    {
        $this->type = $typeName;
        
        $config = [

            'name'  => str_plural($typeName) . 'Connection',
            'model' => GraphQL::type($typeName)->config['model'],

            'fields' => [

                'nodes' => [
                    'type' => Type::listOf(GraphQL::type($typeName)),
                    'resolve' => function ($root) {
                        return $root;
                    },
                ],

                'pageInfo' => [
                    'type' => GraphQL::type('PageInfo'),
                    'resolve' => function ($root, $args, $context) {
                        $pagination = $root->toArray();

                        if (array_key_exists('next_page_url', $pagination)) {
                            $pagination['next_page'] = $pagination['current_page'] + 1;
                        }
                        if (array_key_exists('prev_page_url', $pagination)) {
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
