<?php

namespace Xpromx\GraphQL\Filter;


trait FilterQuery
{

    public function applyFilters( $query, $filters )
    {
  
        foreach( $filters as $filter )
        {
            $method = 'filter_'. $filter['condition'];

            if( method_exists($this, $method ) )
            {
                $query = $this->$method( $query, $filter['field'], $filter['value'] );  
            }
            
        }
  
        return $query;
  
    }

    public function filter_GT($query, $field, $value)
    {
        return $query->where($field, '>', $value);
    }

    public function filter_GTE($query, $field, $value)
    {
        return $query->where($field, '>=', $value);
    }

    public function filter_LT($query, $field, $value)
    {
        return $query->where($field, '<', $value);
    }

    public function filter_LTE($query, $field, $value)
    {
        return $query->where($field, '=<', $value);
    }

    public function filter_EQUAL($query, $field, $value)
    {
        return $query->where($field, '=', $value);
    }

    public function filter_CONTAINS($query, $field, $value)
    {
        return $query->where($field, 'LIKE', '%'.$value.'%');
    }

    public function filter_NOT_CONTAINS($query, $field, $value)
    {
        return $query->where($field, 'NOT LIKE', '%'.$value.'%');
    }

    public function filter_STARTS_WITH($query, $field, $value)
    {
        return $query->where($field, 'LIKE', '%'.$value);
    }

    public function filter_ENDS_WITH($query, $field, $value)
    {
        return $query->where($field, 'LIKE', $value.'%');
    }

    public function filter_IN($query, $field, $value)
    {
        return $query->whereIn($field, explode(',', $value));
    }

    public function filter_NOT_IN($query, $field, $value)
    {
        return $query->whereNotIn($field, explode(',', $value));
    }

}