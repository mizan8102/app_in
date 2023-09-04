<?php 
namespace App\Repositories;

interface StoredProcedureRepositoryInterface
{
    public function callStoredProcedure(string $procedureName,  $parameters);
}
