<?php

namespace App\Services;

use App\Interfaces\ReceiveGate;
use App\Models\RecvChild;
use App\Models\RecvMaster;
use App\Models\RecvMasterPoChild;
use App\Models\SupplierDetail;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReceiveGateService implements ReceiveGate
{


  public function index($data)
  {
    $search = $data["search"] ?? '';
    $paginate = $data['perPage'] ?? 10;
    $supplier_id = $data['supplier_id'] ?? '';
    $ms = $data['ms'] ?? false;
    $ff = RecvMaster::query();
    $home = [];
    if ($ms) {
      $ff->whereNotNull('grn_number');
    } else {
      $ff->whereNull('grn_number');
    }
    if ($supplier_id) {
      $home =  $ff->with(['supplier', 'masterGroup'])
        ->where('supplier_id', $supplier_id)->paginate($paginate);
    } else {
      $home =  $ff->with(['supplier', 'masterGroup'])
        ->where('id', 'like', '%' . $search . '%')
        ->paginate($paginate);
    }


    $suppliers = SupplierDetail::all();
    return [
      'receive_data' => $home,
      'suppliers'    => $suppliers
    ];
  }


  public function create()
  {
    return "create";
  }


  public function store($data, $item)
  {

    try {
      DB::beginTransaction();
      // recv master
      $master = RecvMaster::create($this->storeDto($data));

      // recv child 
      $this->recvChildStore($item, $master->id, true);

      DB::commit();
      return $master;
    } catch (Exception $e) {
      DB::rollBack();
      return $e;
    }
  }

  /**
   * insert data organize  in parent 
   */
  public function storeDto($data, $grnNumber = null)
  {
    return [
      'purchase_order_master_id' => $data['poId'] ?? '',
      'issue_master_id' => $data['issue_master'] ?? '',
      'purchase_order_date' => date('Y-m-d', strtotime($data['purchase_order_date'])) ?? date('Y-m-d'),
      'tran_source_type_id' => $data['tran_source_type_id'] ?? 1,
      'tran_type_id' => $data['tran_type_id'] ?? 1,
      'tran_sub_type_id' => $data['tran_sub_type_id'] ?? 6,
      'prod_type_id' => $data['prod_type_id'] ?? "",
      'vat_rebate_id' => $data['vat_rebate_id'] ?? null,
      'company_id' => Auth::user()->company_id,
      'branch_id' => Auth::user()->branch_id,
      'store_id' => Auth::user()->store_id,
      'currency_id' => $data['currency_id'] ?? 1,
      'excg_rate' => $data['excg_rate'] ?? 1,
      'supplier_id' => $data['supplier_id'] ?? null,
      'reg_status' => $data['reg_status'] ?? 0,
      'supplier_bin_number' => $data['supplier_bin_number'] ?? null,
      'supplier_bin_number_bn' => $data['supplier_bin_number_bn'] ?? 1,
      'bank_branch_id' => $data['bank_branch_id'] ?? null,
      'bank_account_type_id' => $data['bank_account_type_id'] ?? null,
      'is_reg_bank_trans' => $data['tran_source_type_id'] ?? null,
      'supplier_account_number' => $data['supplier_account_number'] ?? 1,
      'receive_date' => date('Y-m-d', strtotime($data['recv_date'])) ?? date('Y-m-d'),
      'fiscal_year_id' => $data['fiscal_year_id'] ?? 1,
      'vat_month_id' => $data['vat_month_id'] ?? 1,
      'grn_number' => $grnNumber,
      'grn_number_bn' =>  $grnNumber,
      'master_group_id' => $data['master_group_id'],
      'grn_date' => date('Y-m-d', strtotime($data['grn_date'])) ?? null,
      'port_discharge' => $data['port_discharge'] ?? null,
      'chalan_type' => $data['chalan_type'] ?? 1,
      'chalan_number' => $data['chalan_number'] ?? 1,
      'chalan_number_bn' => $data['chalan_number_bn'] ?? 1,
      'chalan_date' => date('Y-m-d', strtotime($data['chalan_date'])) ?? date('Y-m-d'),
      'total_cd_amount' => $data['total_cd_amount'] ?? 0,
      'total_rd_amount' => $data['total_rd_amount'] ?? 0,
      'total_sd_amount' => $data['total_sd_amount'] ?? 0,
      'total_vat_amount' => $data['total_vat_amount'] ?? 0,
      'total_at_amount' => $data['total_at_amount'] ?? 0,
      'total_exp_amount' => $data['total_exp_amount'] ?? 0,
      'total_assamble_amount' => $data['total_assamble_amount'] ?? 0,
      'total_receive_amount' => collect($data['item_row'])->sum(function ($item) {
        return $item['orderRate'] * $item['order_quantity'];
      }) ?? 1,
      'total_recv_amt_local_curr' => collect($data['item_row'])->sum(function ($item) {
        return $item['orderRate'] * $item['order_quantity'];
      }) ?? 1,
      'inspection_number' => $data['inspection_number'] ?? null,
      'inspection_number_bn' => $data['inspection_number_bn'] ?? null,
      'monthly_proc_status' => $data['monthly_proc_status'] ?? 0,
      'yearly_proc_status' => $data['yearly_proc_status'] ?? 0,
      'is_vds_done' => $data['is_vds_done'] ?? null,
      'is_tariff' => $data['is_tariff'] ?? null,
      'trariff_val' => $data['trariff_val'] ?? null,
      'trariff_percent' => $data['trariff_percent'] ?? null,
      'import_duty_head' => $data['import_duty_head'] ?? null,
      'duty_percent' => $data['duty_percent'] ?? null,
      'duty_free_amount' => $data['duty_free_amount'] ?? null,
      'duty_currency_id' => $data['duty_currency_id'] ?? null,
      'remarks' => $data['remarks'] ?? null,
      'remarks_bn' => $data['remarks_bn'] ?? null,
      'created_by' => Auth::user()->id,
    ];
  }


  // recv child data store 
  public function recvChildStore($items, $master_id, $gateRecv = false)
  {
    foreach ($items as $key => $itm) {
      if (is_array($itm)) {
        $recvCh = RecvChild::create([
          'receive_master_id' => $master_id,
          'item_info_id'      => $itm['itemId'],
          'uom_id'            => $itm['uom_id'],
          'uom_short_code'    => $itm['uom_short_code'],
          'relative_factor'   => $itm['relative_factor'],
          'vat_payment_method_id' => null,
          'item_cat_for_retail_id' => null,
          'po_qty'            => $itm['order_quantity'],
          'po_rate'           => $itm['orderRate'],
          'gate_recv_qty'     => $itm['recv_qty'],
          'recv_quantity'     => $gateRecv ? $itm['mrecv_qty'] : 0,
          'recv_qty_adjt'     => null,
          'itm_receive_rate'  => $itm['orderRate'],
          'item_value_tran_curr' => $gateRecv ?  $itm['mrecv_qty'] * $itm['orderRate'] : $itm['recv_qty'] * $itm['orderRate'],
          'item_value_local_curr' => $gateRecv ?  $itm['mrecv_qty'] * $itm['orderRate'] : $itm['recv_qty'] * $itm['orderRate'],
          'vat_rate_type_id' => null,
          'is_fixed_rate'     => null,
          'cd_percent'        => 0,
          'cd_amount'         => 0,
          'rd_percent'        => 0,
          'rd_amount'         => 0,
          'sd_percent'        => 0,
          'sd_amount'         => 0,
          'vat_percent'       => 0,
          'fixed_rate_uom_id' => null,
          'fixed_rate'        => 0,
          'vat_amount'        => 0,
          'at_percent'        => 0,
          'at_amount'         => 0,
          'total_amount_local_curr' => $gateRecv ?  $itm['mrecv_qty'] * $itm['orderRate'] : $itm['recv_qty'] * $itm['orderRate'],
          'supplier_vat_percent'  => 0,
          'addtional_vat_percent' => 0,
          'accessable_value' => 0,
          'gate_entry_at'    => now(),
          'gate_entry_by'    => Auth::user()->id,
          'opening_stock_remarks' => null,
          'created_by' => Auth::user()->id,
          'updated_by' => null,
        ]);

        // recv po store 
        $this->recv_master_po_store($itm, $master_id);
      } else {
        return "Item not found";
      }
    }
  }

  // main store data store 

  public function ms_store($data, $item)
  {
    try {
      $grn_num = $this->grnNumber();

      DB::beginTransaction();

      // parent data store
      $master = RecvMaster::create($this->storeDto($data, $grn_num));

      // recv child data store 
      $this->recvChildStore($item, $master->id, true);

      DB::commit();
      return $master;
    } catch (Exception $e) {
      DB::rollBack();
      return $e;
    }
  }

  // store data in trns02b1_recv_master_po_child_qty

  public function recv_master_po_store($itm, $recvMasterId)
  {
    RecvMasterPoChild::create([
      "recv_master_id" => $recvMasterId,
      "po_child_id" => $itm['poChildId'],
      "item_info_id" => $itm['itemId'],
      "recv_qty" => $itm['mrecv_qty'],
      "created_by" => Auth::user()->id,
    ]);
  }

  // grn number generate
  public function grnNumber()
  {
    $comID = Auth::user()->company_id;
    $dataa = DB::select('CALL getTableID("grn_number","' . $comID . '")');
    return $dataa[0]->masterID;
  }



  public function show($id)
  {
    return RecvMaster::with(['recvChild' => function ($query) {
      $query->leftJoin('var_item_info', 'trns02b_recv_child.item_info_id', '=', 'var_item_info.id');
    }, 'supplier', 'masterGroup', 'purchaseOrder'])->find($id);
  }



  public function edit($id)
  {
    return "edit";
  }

  public function update($request, $id)
  {
    return "update";
  }
  public function destroy($id)
  {
    return "destroy";
  }
}
