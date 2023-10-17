<?php

namespace App\Http\Controllers\ModuleTransfer;

use App\Http\Api\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Interfaces\TransferOut;
use Illuminate\Http\Request;

class TransferOutController extends Controller
{
    
    private $transferOut;

    use ApiResponseTrait;

    public function __construct(TransferOut $transferOut)
    {
        $this->transferOut = $transferOut;
    }
    public function index(Request $request)
    {
        $data = [
            "search"    => $request->input('search', ''),
            "perPage"   => $request->input('perPage', 10),
           
        ];

        $response = $this->transferOut->index($data);
        if ($response) {
            return $this->successResponse($response, 'Data retrieved successfully');
        } else {
            return $this->errorResponse('Failed to retrieve data', HttpStatusCodeEnum::BAD_REQUEST);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
