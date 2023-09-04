<?php

namespace App\Http\Controllers;

use App\Repositories\StoredProcedureRepositoryInterface;
use Illuminate\Http\Request;

class StoredProcedureController extends Controller
{
    protected $storedProcedureRepository;
    public function __construct(StoredProcedureRepositoryInterface $storedProcedureRepository)
    {
        $this->storedProcedureRepository = $storedProcedureRepository;
    }
    
    public function callStoredProcedures()
    {
        $results = [];

        // Call the stored procedures without parameters
        $results['procedure1'] = $this->storedProcedureRepository->callStoredProcedure('procedure1_name');
        $results['procedure2'] = $this->storedProcedureRepository->callStoredProcedure('procedure2_name');

        return response()->json($results);
    }
}
