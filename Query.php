<?php

namespace Expresser\Database;

use Illuminate\Database\Query\Builder;

class Query extends \Expresser\Support\Query
{
    protected $passthru = [
        'toSql', 'lists', 'insert', 'insertGetId', 'pluck', 'count',
        'min', 'max', 'avg', 'sum', 'exists', 'getBindings',
    ];

    public function __construct(Builder $query)
    {
        parent::__construct($query);
    }

    public function find($id, $columns = ['*'])
    {
        $this->query->where($this->model->getQualifiedKeyName(), '=', $id);

        return $this->get($columns)->first();
    }

    public function findAll(array $ids, $columns = ['*'])
    {
        $this->query->whereIn($this->model->getQualifiedKeyName(), $ids);

        return $this->get($columns);
    }

    public function first($columns = ['*'])
    {
        return $this->take(1)->get($columns)->first();
    }

    public function get($columns = ['*'])
    {
        $results = $this->query->get($columns);

        return $this->getModels($results);
    }

    public function __call($method, $parameters)
    {
        $result = call_user_func_array([$this->query, $method], $parameters);

        return in_array($method, $this->passthru) ? $result : $this;
    }
}
