<?php

namespace App\Http\Requests\Receive;


use Illuminate\Foundation\Http\FormRequest;

class ReceiveGateRequest extends FormRequest
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
     * @return array, mixed>
     */
    public function rules()
    {
        return [
            'issue_master' => 'nullable',
            'purchase_order_date' => 'nullable',
            'tran_source_type_id' => 'nullable',
            'master_group_id'   => 'required',
            'prod_type_id' => 'nullable',
            'recv_date' => 'required|date',
            'poId' => 'nullable',
            'vat_rebate_id' => 'nullable',
            'supplier_id' => 'nullable',
            'supplier_bin_number' => 'nullable',
            'bank_branch_id' => 'nullable',
            'bank_account_type_id' => 'nullable',
            'is_reg_bank_trans' => 'nullable',
            'supplier_account_number' => 'nullable',
            'receive_date' => 'nullable',
            'fiscal_year_id' => 'nullable',
            'vat_month_id' => 'nullable',
            'grn_number' => 'nullable',
            'grn_number_bn' => 'nullable',
            'grn_date' => 'nullable',
            'port_discharge' => 'nullable',
            'chalan_type' => 'nullable',
            'chalan_number' => 'nullable',
            'chalan_number_bn' => 'nullable',
            'chalan_date' => 'nullable',
            'total_cd_amount' => 'nullable|numeric',
            'total_rd_amount' => 'nullable|numeric',
            'total_sd_amount' => 'nullable|numeric',
            'total_vat_amount' => 'nullable|numeric',
            'total_at_amount' => 'nullable|numeric',
            'total_exp_amount' => 'nullable|numeric',
            'total_assamble_amount' => 'nullable|numeric',
            'total_receive_amount' => 'nullable|numeric',
            'total_recv_amt_local_curr' => 'nullable|numeric',
            'inspection_number' => 'nullable',
            'inspection_number_bn' => 'nullable',
            'monthly_proc_status' => 'nullable',
            'yearly_proc_status' => 'nullable',
            'is_vds_done' => 'nullable',
            'is_tariff' => 'nullable',
            'trariff_val' => 'nullable|numeric',
            'trariff_percent' => 'nullable|numeric',
            'import_duty_head' => 'nullable',
            'duty_percent' => 'nullable|numeric',
            'duty_free_amount' => 'nullable|numeric',
            'duty_currency_id' => 'nullable',
            'remarks' => 'nullable',
            'remarks_bn' => 'nullable',
            'created_by' => 'nullable',
            'item_row' => 'array'
        ];
    }
}
