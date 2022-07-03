<?php

namespace App\Http\Controllers\Datatable;

use App\Http\Controllers\Controller;
use ArrayAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Arr;

abstract class DatatableController extends Controller
{
    /**
     * @var bool
     */
    protected bool $allowCreation = true;

    /**
     * @var
     */
    protected mixed $builder;

    /**
     * @return mixed
     */
    abstract public function builder(): mixed;

    /**
     * CONSTRUCT THE ELOQUENT BUILDER
     */
    public function __construct()
    {
        $builder = $this->builder();
        $this->builder = $builder;
    }

    /**
     * CONFIG THE INDEX CORRESPONDING TO THE OBJECT
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        return response()->json([
            'data' => [
                'table' => $this->builder->getModel()->getTable(),
                'displayables' => array_values($this->getDisplayableColumns()),
                'updatables' => array_values($this->getUpdatableColumns()),
                'custom_columns' => $this->getCustomColumnNames(),
                'records' => $this->getRecords($request),
                'allow' => [
                    'creation' => $this->allowCreation
                ]
            ]
        ]);
    }

    /**
     * UPDATE THE OBJECT ACCORDING TO IT'S 'ID'
     * @param $id
     * @param Request $request
     * @return void
     */
    public function update($id, Request $request)
    {
        $this->builder->find($id)->update($request->only($this->getUpdatableColumns()));
    }

    /**
     * CONFIGURE THE DISPLAYABLE COLUMNS
     * ONLY THESE COLUMNS ARE SEARCHABLE
     * @return array
     */
    public function getDisplayableColumns(): array
    {
        return array_diff($this->getDatabaseColumnNames(), $this->builder->getModel()->getHidden());
    }

    /**
     * CONFIGURE THE UPDATABLE COLUMNS
     * @return array
     */
    public function getUpdatableColumns(): array
    {
        return $this->getDisplayableColumns();
    }

    /**
     * GET THE DATABASE COLUMN NAME
     * @return array
     */
    public function getDatabaseColumnNames(): array
    {
        return Schema::getColumnListing($this->builder->getModel()->getTable());
    }

    /**
     * GET CUSTOM COLUMN NAMES.
     * @return array
     */
    public function getCustomColumnNames()
    {
        return [];
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getRecords(Request $request)
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
     * @return array|ArrayAccess|mixed
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
                'value' => "%$value%"
            ],
            'starts_with' => [
                'operator' => 'like',
                'value' => "$value%"
            ],
            'ends_with' => [
                'operator' => 'like',
                'value' => "%$value"
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