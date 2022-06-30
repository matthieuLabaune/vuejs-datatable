<?php

namespace App\Http\Controllers\Datatable;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

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
    }

    public function index()
    {
    }
}
