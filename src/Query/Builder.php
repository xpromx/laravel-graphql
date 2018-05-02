<?php
namespace Xpromx\GraphQL\Query;

use GraphQL;

trait Builder
{
    public function makeRelations($query, $info)
    {
        $type = $this->getType($info->returnType);
        $fields = $info->getFieldSelection(2); // get fields from graphql query
        if (key($fields) == 'nodes') {
            $fields = $fields['nodes'];
        }

        $queryFields = $this->getFields($type, $fields);
        $queryRelations = $this->getRelations($type, $fields); // relations in this query
       
        if (count($queryRelations) > 0) {
            foreach ($queryRelations as $field=>$type) {
                $relation = $query->getModel()->$field();

                if (!str_contains(get_class($relation), 'belongsToMany')) {
                    continue;
                }

                $queryRelationFields = $this->getFields($type, $fields[ $field ]);
                
                if (!str_contains(get_class($relation), 'Many')) {
                    if (method_exists($relation, 'getForeignKey')) {
                        $queryFields[] = $relation->getForeignKey();
                    }
                }

                $query->with($field . ':' . implode(',', $queryRelationFields));
            }
        }

        $query->select($queryFields);
        
        return $query;
    }

    public function getRelationName($root, $method)
    {
        $relation = false;

        // check method
        if (method_exists($root, $method)) {
            return $method;
        }
        
        // plural method
        if (!$relation && method_exists($root, str_plural($method))) {
            return str_plural($method);
        }
        
        // singular method
        if (!$relation && method_exists($root, str_singular($method))) {
            return str_singular($method);
        }

        return $relation;
    }

    public function getFields($type, $queryFields)
    {
        $type = GraphQL::type($type);
        $table = app()->make($type->config['model'])->getTable();

        // get fields names
        foreach ($type->getFields() as $field) {
            if (isset($field->config['field']) || (!isset($field->config['relation']) && !isset($field->config['ignore']))) {
                $key = (isset($field->config['field']) ? $field->config['field'] : $field->name);
                $fields[ $field->name ] = $key;
            }
        }

        $selectedFields['id'] = $table .'.id';

        // only keep the ones we selected
        foreach ($queryFields as $key=>$field) {
            if (isset($fields[ $key ])) {
                $selectedFields[] = $table . '.' . $fields[ $key ];
            }
        }

        return $selectedFields;
    }

    public function getRelations($type, $queryFields)
    {
        $type = GraphQL::type($type);

        $relations = [];

        foreach ($type->getFields() as $field) {
            if (isset($field->config['relation']) && isset($queryFields[$field->name])) {
                $relations[$field->name] = $this->getType($field->getType());
            }
        }

        return $relations;
    }

    public function getType($object)
    {
        if (property_exists($object, 'type')) {
            return $object->type;
        }

        if (!property_exists($object, 'config')) {
            return $object->ofType->config['name'];
        }

        return $object->config['name'];
    }
}
