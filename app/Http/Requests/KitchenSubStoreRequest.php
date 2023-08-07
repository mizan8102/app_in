<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KitchenSubStoreRequest extends FormRequest
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
            'prod_type_id' => 'required',
            'indent_date'  => 'required|date',
            'remarks'      => 'nullable|max:200',
            'indentItems'  => 'required|array|min:1',

            'indentItems.*.item_information_id' => 'required',
            'indentItems.*.indent_qty'          => 'required|integer|min:0',
            'indentItems.*.required_date'       => 'required|date',

            'submited_by'     => 'required',
            'approved_by'     => 'required',
            'recommented_by'  => 'required',
            'demand_store_id' => 'required',
            'to_store_id' => 'required',
        ];
    }


    public function prepareforvalidation()
    {
        $this->merge([
            'company_id'     => Auth::user()->company_id,
            'demand_store_id' => Auth::user()->store_id,
            'submited_by'    => Auth::id(),
            'approved_by'    => Auth::id(),
            'recommented_by' => Auth::id(),
            'children'       => $this->parseIndentChild($this->indentItems)
        ]);
    }



    private function parseIndentChild($data)
    {
        $childern = [];
        foreach ($data as $item) {
            $product = DB::table('var_item_info')->where('item_information_id', $item['item_information_id'] ?? '')->first();
            $childern[] = [
                'item_information_id' => $item['item_information_id'] ?? '',
                'uom_id' => $product->uom_id ?? false,
                'uom_short_code' => $item['uom_code'] ?? '',
                'indent_quantity' => $item['indent_qty'] ?? '',
                'required_date' => Carbon::parse($item['required_date'] ?? '')->format('Y-m-d'),
                'Remarks' => $item['remarks'] ?? '',
            ];
        }

        return $childern;
    }
}
