<?php 
namespace App\Services;
use App\Http\Resources\Receive\ReceiveGateResource;
use App\Interfaces\ReceiveGate;
use App\Models\RecvMaster;
class ReceiveGateService implements ReceiveGate{

  
  public function index($data){
    $search = $data["search"] ?? '';
    $paginate = $data['perPage'] ?? 10;
    $supplier_id = $data['supplier'] ?? '';
    return ReceiveGateResource::collection(RecvMaster::with(['supplier','masterGroup'])
    ->where('id', 'like', '%' . $search . '%')
    ->where('supplier_id','like', '%' . $supplier_id . '%')->paginate($paginate));
  }


  public function create(){

  }


  public function store($request){

  }

  public function show($id){

  }

  public function edit($id){

  }

  public function update( $request, $id){

  }
  public function destroy($id){

  }
}