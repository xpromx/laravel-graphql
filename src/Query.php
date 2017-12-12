<?php

namespace Xpromx\GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use Folklore\GraphQL\Support\Query as BaseQuery;
use Xpromx\GraphQL\Filter\FilterQuery;
use Xpromx\GraphQL\Query\Args;
use Xpromx\GraphQL\Query\Builder;

class Query extends BaseQuery
{
    use FilterQuery;
    use Args;
    use Builder;

    public $single = false;

    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        $query = app()->make( $info->returnType->config['model'] )->query();

        $query = $this->makeQuery( $query, $args, $info );
        
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

    public function makeQuery( $query, $args, $info )
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

}