<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndentRequsitionRequest extends FormRequest
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
            'indent_number' =>'required',
            'requisition_number' => 'nullable',
            'requisition_date' => 'required',
            'submitted_by' => 'required',
            'is_Status' => 'required',
            'recommended_by' => 'required',
            'approved_by' => 'required',
            'totalAmount' => 'required',
            'remarks' => 'nullable',
            'requisitions_child' => 'array'
        ];
    }
}
