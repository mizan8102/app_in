<?php

namespace App\Services;

use App\Interfaces\TransferOut;
use App\Models\TransferMaster;

class TransferOutService implements TransferOut
{

public function index($data){
  $search = $data["search"] ?? '';
  $paginate = $data['perPage'] ?? 10;

  // transfer service 
  return TransferMaster::with('productreq')
   ->where('id', 'like', '%' . $search . '%')->paginate($paginate);
}


  public function create(){
  
}


  public function store($request,$item_row){
  
}
  public function ms_store($request,$item_row){
  
}

  public function show($id){
  
}

  public function edit($id){
  
}

  public function update($request, $id){
  
}
  public function destroy($id){
  
}


}