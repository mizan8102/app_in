<?php

namespace App\Http\Controllers\ModuleReceive;

use App\Enums\HttpStatusCodeEnum;
use App\Http\Api\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Receive\ReceiveGateRequest;
use App\Interfaces\ReceiveGate;
use Illuminate\Http\Request;

class ReceiveMainStoreController extends Controller
{

    private $receiveGate;

    use ApiResponseTrait;

    public function __construct(ReceiveGate $receiveGate)
    {
        $this->receiveGate = $receiveGate;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $data  = $receiveGateRequest->validated();
        $res = $this->receiveGate->ms_store($data, $data['item_row']);
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
