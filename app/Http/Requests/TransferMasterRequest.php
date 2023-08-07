<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferMasterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
              'indent_master_id'=> 'required',
              'transfer_date'=> 'required',
              'challan_number'=> 'nullable',
              'challan_number_bn'=> 'nullable',
              'challan_date'=> 'required',
              'issuing_store_id'=> 'nullable',
              'receiving_store_id'=> 'nullable',
              'issue_master_id'=> 'nullable',
              'receive_master_id'=> 'nullable',
              'transfer_status'=> 'nullable',
              'vehicle_no'=> 'nullable',
              'total_item_amount'=> 'nullable',
              'total_amount_bn'=> 'nullable',
              'total_sd_amount'=> 'nullable',
              'total_sd_amount_bn'=> 'nullable',
              'total_vat_amount'=> 'nullable',
              'total_vat_amount_bn'=> 'nullable',
              'monthly_process_status'=> 'nullable',
              'yearly_process_status'=> 'nullable',
              'remarks'=> 'nullable',
              'created_at'=> 'nullable',
              'updated_at'=> 'nullable',
              'created_by'=> 'nullable',
              'updated_by'=> 'nullable'
        ];
    }
}
