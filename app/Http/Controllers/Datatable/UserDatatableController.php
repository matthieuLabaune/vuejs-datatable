<?php

namespace App\Http\Controllers\Datatable;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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

    public function getUpdatableColumns(): array
    {
        return [
            'name', 'email', 'created_at'
        ];
    }

    public function getCustomColumnNames(): array
    {
        return [
            'email' => 'Adresse email',
            'name' => 'Identité',
            'created_at' => 'Création'
        ];
    }

    /**
     * @throws ValidationException
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email'
        ]);

        $this->builder->find($id)->update($request->only($this->getUpdatableColumns()));
    }
}
