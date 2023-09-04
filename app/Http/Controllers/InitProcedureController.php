<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\StoredProcedureRepositoryInterface;

class InitProcedureController extends Controller
{
    protected $storedProcedureRepository;
    public function __construct(StoredProcedureRepositoryInterface $storedProcedureRepository)
    {
        $this->storedProcedureRepository = $storedProcedureRepository;
    }
    
    public function callStoredProcedures($procedureName)
    {
        $id = request('no',null);
        return $this->storedProcedureRepository->callStoredProcedure($procedureName,$id);
    }
}
