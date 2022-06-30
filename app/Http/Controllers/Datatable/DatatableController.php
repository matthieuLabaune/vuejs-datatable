<?php

namespace App\Http\Controllers\Datatable;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
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

    /**
     *
     */
    public function __construct()
    {
        $builder = $this->builder();

        $this->builder = $builder;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {

        return response()->json([
            'data' => [
                'displayables' => array_values($this->getDisplayableColumns()),
                'records' => $this->getRecords(),
            ]
        ]);

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
    public function getDatabaseColumnNames(): array
    {
        return Schema::getColumnListing($this->builder->getModel()->getTable());

    }

    /**
     * @return mixed
     */
    protected function getRecords(): mixed
    {
        return $this->builder->get($this->getDisplayableColumns());
    }
}
