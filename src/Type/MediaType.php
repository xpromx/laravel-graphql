<?php
namespace Xpromx\GraphQL\Type;

use Xpromx\GraphQL\Definition\Type;
use Xpromx\GraphQL\Type as BaseType;

class MediaType extends BaseType
{
    protected $attributes = [
        'name' => 'Media',
        'description' => 'A media item',
        'model' => \App\Models\Media::class,
        'select' => 'media.*'
     ];
    public function fields()
    {
        return [
        'url' => [
          'type' => Type::string(),
          'ignore' => true,
          'args' => [
            'size' => [
              'type' => Type::string(),
              'defaultValue' => ''
            ]
          ]
        ],

        'name' => [
          'type' => Type::string()
        ],

        'fullUrl' => [
          'type' => Type::string(),
          'ignore' => true,
        ],

        'path' => [
          'type' => Type::string(),
          'ignore' => true,
        ]
      ];
    }
    public function resolveUrlField($root, $args)
    {
        return $root->getUrl($args['size']);
    }
    public function resolveFullUrlField($root, $args)
    {
        return $root->getFullUrl();
    }
    public function resolvePathField($root, $args)
    {
        return $root->getFullUrl();
    }
}
