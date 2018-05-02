<?php
namespace Xpromx\GraphQL\Type;

use Xpromx\GraphQL\Definition\Type;
use Xpromx\GraphQL\Type as BaseType;

class MediaType extends BaseType
{
    protected $attributes = [
        'name' => 'Media',
        'description' => 'A media item',
        'model' => \App\Models\Media::class
     ];
    public function fields()
    {
        return [
        'url' => [
          'type' => Type::string()
        ],
        'name' => [
          'type' => Type::string()
        ],
        'fullUrl' => [
          'type' => Type::string()
        ],
        'path' => [
          'type' => Type::string()
        ]
      ];
    }
    public function resolveUrlField($root, $argd)
    {
        return $root->getUrl();
    }
    public function resolveFullUrlField($root, $argd)
    {
        return $root->getFullUrl();
    }
    public function resolvePathField($root, $argd)
    {
        return $root->getFullUrl();
    }
}
