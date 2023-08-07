<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgramUpdateRequest extends FormRequest
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
            'store_id' => 'required',
            'floor_id' => 'required',
            'customer_id' => 'required',
            'program_type_id' => 'required',
            'program_name' => 'required',
            'program_name_bn' => 'required',
            'hall_room_charge_vat_per' => 'required',
            'prog_date' => 'nullable',
            'vat_on_food' => 'required',
            'remarks' => 'required',
            'prog_start_time' => 'required',
            'prog_end_time' => 'required',
            'number_of_guest' => 'required',
            'hall_room_charge' => 'required',
            'total_amount_without_vat' => 'required',
            'hall_room_vat' => 'required',
            'food_vat_per' => 'required',
            'food_charge' => 'required',
            'total_amount' => 'required',
            'vat_amount' => 'required',
            'total_amount_with_vat' => 'required',
            'is_active' => 'nullable',
            'is_print' => 'nullable',
            'created_at' => 'nullable',
            'updated_at' => 'nullable',
            'created_by' => 'nullable',
            'updated_by' => 'nullable',

        ];
    }
}
