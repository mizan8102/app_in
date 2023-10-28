<?php

namespace App\Http\Requests\Transfer;

use Illuminate\Foundation\Http\FormRequest;

class TransferOutRequest extends FormRequest
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
           "indent_id"      => "required",
           "store"          => "required",
           "store_name"     => "required",
           "transferDate"   => "required",
           "challan_date"   => "required",
           "challan_no"     => "required",
           "prod_req_date"  => "required",
           "remarks"        => "nullable",
           "item_row"       => "required|array",
        ];
    }
}
