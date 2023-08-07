<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemStockMasterRequest extends FormRequest
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
            'receive_Issue_master_id', 
            'tran_source_type_id', 
            'tran_type_id', 
            'tran_sub_type_id', 
            'prod_type_id', 
            'vat_rebate_id', 
            'company_id', 
            'branch_id', 
            'currency_id', 
            'receive_issue_date', 
            'opening_bal_date', 
            'fiscal_year', 
            'vat_month', 
            'supplier_id', 
            'supplier_bin_number', 
            'supplier_bin_number_bn', 
            'supplier_bank_branch_id', 
            'supplier_bank_account_type_id', 
            'supplier_account_number', 
            'supplier_is_reg_bank_trans', 
            'customer_id', 
            'customer_bin_number', 
            'customer_bin_number_bn', 
            'customer_bank_branch_id', 
            'customer_bank_account_type_id', 
            'customer_account_number', 
            'customer_is_reg_bank_trans', 
            'item_information_id', 
            'uom_id', 
            'issue_for', 
            'is_sub_contract', 
            'sub_contractor_name', 
            'sub_contractor_name_bn', 
            'prod_process', 
            'vat_payment_method_id', 
            'item_cat_for_retail_id', 
            'vat_rate_type_id', 
            'challan_number', 
            'challan_number_bn', 
            'challan_date', 
            'vat_challan_number', 
            'vat_challan_number_bn', 
            'vat_challan_date', 
            'remarks', 
            'remarks_bn', 
            'created_at', 
            'updated_at', 
            'created_by', 
            'updated_by'
        ];
    }
}
