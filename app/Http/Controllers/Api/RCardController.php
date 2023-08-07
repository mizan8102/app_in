<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RCard;

class RCardController extends Controller
{ 
    public function checkUserCardNo(Request $request)
    { 
        return RCard::select(
            "id",
            "card_category_id",
            "card_number",
            "card_type",
            "customer_id",
            "restaurant_master_id",
            "is_active",
            "is_free",
        )
            ->with([
                'customer_detail' => function ($query) {
                    $query->select('company_id', 'customer_id', 'phone_number');
                },
            ])
            ->where('card_number', $request->card_no)
            ->first();
    }
}
