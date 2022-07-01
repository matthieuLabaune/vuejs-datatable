<?php

namespace App\Http\Controllers\Datatable;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

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
     * @return mixed
     */
    protected function getRecords(Request $request): mixed
    {
        return $this->builder->limit($request->limit)->orderBy('id')->get($this->getDisplayableColumns());
    }
}
