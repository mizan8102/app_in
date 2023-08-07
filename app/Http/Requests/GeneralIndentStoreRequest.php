<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GeneralIndentStoreRequest extends FormRequest
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
            'indent_date'=>'required|date',
            'to_store_id'=>'required',
            'pro_category'=>'required|numeric',
            'pro_type'=>'required|numeric',
            'master_group'=>'required|numeric',
            'group'=>'required|numeric',
            'sub_group'=>'required|numeric',
            'remarks'=>'required|string|max:255',
            'item_row.*.item_information_id'=>'required|numeric',
            'item_row.*.indent_qty'=>'required|numeric',
            'item_row.*.uom_id'=>'required|numeric',
            'item_row.*.indent_req_date'=>'required|date',
            'item_row.*.indent_comment'=>'sometimes|string|max:255',
        ];
    }
}