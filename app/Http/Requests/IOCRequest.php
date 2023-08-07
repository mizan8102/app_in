<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IOCRequest extends FormRequest
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
            "prc_decl_name" => 'required',
            "prc_decl_number" => 'required|unique:tran01a_ioc_price_declaration,prc_decl_number',
            "effective_from" => 'required',
            "item_information_id" => 'required',
            "total_cost_rm" => 'required',
            "total_overhead_cost" => 'required',
            "total_monthly_srv_cost" =>'required',
            "is_manufactured_itm" => 'nullable',
            "total_cost" => 'required',
            "date_of_submission" => 'required',
            "remarks" => 'nullable',
            "itemInfoRows"=>'array',
            "inputServiceRows"=>'array',
            "valueAddedRows"=>'array'
        ];
    }
}
