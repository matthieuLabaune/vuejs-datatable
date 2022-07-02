<?php

namespace App\Http\Controllers\Datatable;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Arr;

abstract class DatatableController extends Controller
{
    /**
     * @var
     */
    protected $builder;

    /**
     * @return mixed
     */
    abstract public function builder(): mixed;

    public function __construct()
    {
        $builder = $this->builder();

        $this->builder = $builder;
    }

    /**
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'data' => [
                'table' => $this->builder->getModel()->getTable(),
                'displayables' => array_values($this->getDisplayableColumns()),
                'updatables' => array_values($this->getUpdatableColumns()),
                'records' => $this->getRecords($request),

            ]
        ]);
    }

    public function update($id, Request $request)
    {
        $this->builder->find($id)->update($request->only($this->getUpdatableColumns()));
    }

    /**
     * @return array
     */
    public function getDisplayableColumns(): array
    {
        return array_diff($this->getDatabaseColumnNames(), $this->builder->getModel()->getHidden());
    }

    /**
     * @return array
     */
    public function getUpdatableColumns(): array
    {
        return $this->getDisplayableColumns();
    }

    /**
     * @return array
     */
    public function getDatabaseColumnNames(): array
    {
        return Schema::getColumnListing($this->builder->getModel()->getTable());
    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function getRecords(Request $request): mixed
    {
        $builder = $this->builder;

        if ($this->hasSearchQuery($request)) {
            $builder = $this->buildSearch($builder, $request);
        }

        try {
            return $this->builder->limit($request->limit)->orderBy('id')->get($this->getDisplayableColumns());
        } catch (QueryException $exception) {
            return [];
        }


    }

    /**
     * @param Request $request
     * @return bool
     */
    protected function hasSearchQuery(Request $request): bool
    {
        return count(array_filter($request->only(['column', 'operator', 'value']))) === 3;
    }

    /**
     * @param Builder $builder
     * @param Request $request
     * @return Builder
     */
    protected function buildSearch(Builder $builder, Request $request): Builder
    {
        $queryParts = $this->resolveQueryParts($request->operator, $request->value);
        return $builder->where($request->column, $queryParts['operator'], $queryParts['value']);
    }

    /**
     * @param $operator
     * @param $value
     * @return array|\ArrayAccess|mixed
     */
    protected function resolveQueryParts($operator, $value): mixed
    {
        return Arr::get([
            'equals' => [
                'operator' => '=',
                'value' => $value
            ],
            'contains' => [
                'operator' => 'like',
                'value' => "%{$value}%"
            ],
            'starts_with' => [
                'operator' => 'like',
                'value' => "{$value}%"
            ],
            'ends_with' => [
                'operator' => 'like',
                'value' => "%{$value}"
            ],
            'greater_than' => [
                'operator' => '>',
                'value' => $value
            ],
            'less_than' => [
                'operator' => '<',
                'value' => $value
            ],
        ], $operator);
    }
}