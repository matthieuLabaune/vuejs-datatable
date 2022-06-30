<?php

namespace App\Http\Controllers\Datatable;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserDatatableController extends DatatableController
{
    //
    public function builder(): Builder
    {
        return User::query(); //Builder
    }

    public function getDisplayableColumns(): array
    {
        return [
            'id', 'name', 'email', 'created_at'
        ];
    }
}
