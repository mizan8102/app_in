<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemIndentRequest extends FormRequest
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
            'floor_id' =>'required',
            'floor_name' => 'required',
            'prog_date' => 'required',
            'program_master_id' => 'required',
            'prod_type_id' => 'required',
            'indent_number' => 'required',
            'indent_date' => 'required',
            'remarks' => 'nullable',
            'submitted_by' => 'required',
            'recommended_by' => 'nullable',
            'approved_by' => 'nullable',
            'indent_child' => 'array',
            
        ];

    }
}
