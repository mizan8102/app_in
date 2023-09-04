<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class StoredProcedureRepository implements StoredProcedureRepositoryInterface
{
    public function callStoredProcedure(string $procedureName,  $parameters)
    {
        return $parameters ? DB::select("CALL $procedureName('$parameters')") : DB::select("CALL $procedureName()");
    }
}
