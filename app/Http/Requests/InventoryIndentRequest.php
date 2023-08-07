<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventoryIndentRequest extends FormRequest
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

            'program_master_id' => 'nullable',
            'prod_type_id' => 'nullable',
            'indent_number' => 'nullable',
            'indent_date' => 'required',
            'remarks' => 'nullable',
            'grp_master' => 'required',
            'pro_cat' => 'required',
            'pro_type' => 'required',
            'company_id' => 'nullable',
            'branch_id' => 'nullable',
            'demand_store' => 'nullable',
            'to_store_id' => 'required',
            'issue_status' => 'nullable',
            'close_status' => 'nullable',
            'submitted_by' => 'nullable',
            'recommended_by' => 'nullable',
            'approved_by' => 'nullable',
            'item_row' => 'array',

        ];
    }
}
