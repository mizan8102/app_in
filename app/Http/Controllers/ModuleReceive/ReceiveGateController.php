<?php

namespace App\Http\Controllers\ModuleReceive;

use App\Enums\HttpStatusCodeEnum;
use App\Http\Api\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Receive\ReceiveGateRequest;
use App\Interfaces\ReceiveGate;
use Illuminate\Http\Request;

class ReceiveGateController extends Controller
{
    private $receiveGate;

    use ApiResponseTrait;

    public function __construct(ReceiveGate $receiveGate)
    {
        $this->receiveGate = $receiveGate;
    }



    public function index(Request $request)
    {
        $data = [
            "search"    => $request->input('search', ''),
            "perPage"   => $request->input('perPage', 10),
            "supplier_id" => $request->input('supplier_id', ''),
            "ms"          => $request->input('ms', false)
        ];

        $response = $this->receiveGate->index($data);
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
    public function store(ReceiveGateRequest $receiveGateRequest)
    {
        $data   = $receiveGateRequest->validated();
        $res    = $this->receiveGate->store($data, $data['item_row']);
        if ($res) {
            return $this->successResponse($res, "Data retrive successfull");
        } else {
            return $this->errorResponse('Failed to retrieve data', HttpStatusCodeEnum::BAD_REQUEST);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
        $res = $this->receiveGate->show($id);
        if ($res) {
            return $this->successResponse($res, "Data retrive successfull");
        } else {
            return $this->errorResponse('Failed to retrieve data', HttpStatusCodeEnum::BAD_REQUEST);
        }
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
