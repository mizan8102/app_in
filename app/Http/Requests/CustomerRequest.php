<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'company_id' => 'required',
            'customer_name' => 'required',
            'customer_name_bn' => 'required',
            'phone_number' => 'required',
            'address' => 'required',
            'is_active' => 'required',
            'registration_status' => 'required',
        ];
    }
}
