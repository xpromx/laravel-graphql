<?php

namespace Xpromx\GraphQL;

use GraphQL\Type\Definition\Type as TypeDefinition;
use Folklore\GraphQL\Support\Type as BaseType;

class Type extends BaseType
{

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    public function fields()
    {
        return [

            'id' => [
				'type' => TypeDefinition::nonNull(TypeDefinition::ID()),
            ],

            'created_at' => [
				'type' => TypeDefinition::date(),
            ],

            'updated_at' => [
				'type' => TypeDefinition::date(),
            ],

        ];
    }
    
}