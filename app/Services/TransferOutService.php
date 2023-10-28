<?php

namespace App\Services;

use App\DTO\IssueChildDTO;
use App\DTO\IssueMasterDTO;
use App\DTO\TransferOutDTO;
use App\Interfaces\TransferOut;
use App\Models\IssueChild;
use App\Models\IssueMaster;
use App\Models\TransferMaster;
use Illuminate\Support\Facades\DB;

class TransferOutService implements TransferOut
{

  public function index($data)
  {
    $search = $data["search"] ?? '';
    $paginate = $data['perPage'] ?? 10;

    // transfer service 
    return TransferMaster::with('productreq')
      ->where('id', 'like', '%' . $search . '%')->paginate($paginate);
  }


  public function create()
  {
  }

  /**
   * @param mixed $request
   * @param mixed $item_row
   * @todo  Store Data in 
   *        i. Issue Master and Issue child Table
   *        ii. Transfer Out 
   *        iii. Stock Master and Stock Child
   * @method mixed issue_number() return issue number
   */

  public function store($request, $item_row)
  {
    $issueNumber = issue_number();
    $issuemTotalAmt = $this->issueTotalAmnt($item_row);
    $issueMasterDTO = new IssueMasterDTO($request, $issueNumber, $issuemTotalAmt);
    try {
      DB::beginTransaction();
      $issueMaster = IssueMaster::create($issueMasterDTO->toArray());
      $transferDTO = new TransferOutDTO($request, $issueMaster->id);
      $trnMaster = TransferMaster::create($transferDTO->toArray());
      
    
      DB::commit();
      return $issueMaster;
    } catch (\Exception $e) {
      DB::rollBack();
      return $e->getMessage();
    }
  }

  // issue child 
  public function issueChildStore($issueMaster, $item_row){
    $issueChildren = array();
    foreach($item_row as $item){
      $issueChildDTO = new IssueChildDTO($item, $issueMaster->id);
      array_push($issueChildren,$issueChildDTO) ;
    }
  }


  public function lineQtyIssueChild($item){

  }


  // stock qty and amount return 
  public function stockQtyAndPrice($id){
    return 1;
  }
  // issueTotal Amount
  public function issueTotalAmnt($item_row){
   return collect($item_row)->sum(function ($item) {
        return $item['orderRate'] * $item['order_quantity'];
      });
  }


  public function show($id)
  {
  }

  public function edit($id)
  {
  }

  public function update($request, $id)
  {
  }
  public function destroy($id)
  {
  }
}
