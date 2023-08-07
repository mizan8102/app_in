<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProgramRequest extends FormRequest
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
            'store_id' => 'nullable|exists:cs_company_store_location,id',
            'floor_id' => 'required|exists:r_floor,id',
            'customer_id' => 'required|exists:cs_customer_details,id',
            'program_type_id' => 'required|exists:r_program_type,id',
            'program_name' => 'required|max:255',
            'program_name_bn' => 'required|max:255',
            'hall_room_charge_vat_per' => 'required|numeric',
            'prog_date' => 'required|date|after_or_equal:today',
            'ride_charge' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'service_charge' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'discount' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'program_session_id' => 'required|exists:program_sessions,id',
            'new_guest' => 'nullable|boolean',
            'vat_on_food' => 'required|numeric',
            'remarks' => 'nullable|max:255',
            'prog_start_time' => 'nullable|date',
            'prog_end_time' => 'nullable|date',
            'number_of_guest' => 'required|integer|min:1',
            'hall_room_charge' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'total_amount_without_vat' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'hall_room_vat' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'food_vat_per' => 'required|numeric',
            'food_charge' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'total_amount' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'vat_amount' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'total_amount_with_vat' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'grandTotal' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'due_amount' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'is_active' => 'nullable|boolean',
            'is_print' => 'nullable|boolean',
            'paid_amount' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'childs' => 'nullable|array',
            'childs.*.menu_id' => 'required|integer|exists:p_program_menu,id',
            'childs.*.uom' => 'required',
            'childs.*.prod_type_id' => 'required|integer|exists:5f_sv_product_type,id',
            'childs.*.menu_qty' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'childs.*.menu_rate' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'childs.*.menu_amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ];
    }
}