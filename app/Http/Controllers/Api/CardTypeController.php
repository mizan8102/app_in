<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\CardType;
use App\Models\CardCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CardTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request()->input('search');
        $perPage = request()->input('perPage', 10);
        $cardType = CardType::query()
        ->leftJoin('card_categories','card_categories.id','card_types.card_category_id')
            ->when($search, function ($query, $search) {
                return $query->where('card_type_name', 'like', '%' . $search . '%')
                    ->orWhere('card_type_name_bn', 'like', '%' . $search . '%');
            })
            ->orderBy('card_type_name', 'ASC')
            ->select('card_types.*','card_categories.card_cat_name as card_cat_name')
            ->paginate($perPage);
        return sendJson('list of the card type', $cardType, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $search = request()->input('search');
        $cardCategory = CardCategory::query()
            ->when($search, function ($query, $search) {
                return $query->where('card_cat_name', 'like', '%' . $search . '%')
                    ->orWhere('card_cat_name_bn', 'like', '%' . $search . '%');
            })
            ->orderBy('card_cat_name', 'ASC')
            ->get();
        return sendJson('list of the card category', $cardCategory, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'card_category_id' => 'required|numeric|exists:card_categories,id',
            'card_type_name' => 'required|max:45',
            'card_type_name_bn' => 'required|max:45',
            'is_active' => 'required|boolean|max:1|min:0',
        ]);
        // if ($validated->fails()) {
        //     return sendJson('validation fails', $validated->errors(), 422);
        // }
        try {
            DB::beginTransaction();
            $cardType = CardType::create([
                'card_category_id' => $request->card_category_id,
                'card_type_name' => $request->card_type_name,
                'card_type_name_bn' => $request->card_type_name_bn,
                'is_active' => $request->is_active,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
            DB::commit();
            return sendJson('card type create success', $cardType, 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return sendJson('card type create success', $th->getMessage(), 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cardType = CardType::find($id);
        if ($cardType) {
            return sendJson('card type found', $cardType, 200);
        } else {
            return sendJson('card type not found', null, 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cardType = CardType::find($id);
        if ($cardType) {
            return sendJson('card type found', $cardType, 200);
        } else {
            return sendJson('card type not found', null, 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cardType = CardType::find($id);
        if ($cardType) {
            $validated = Validator::make($request->all(), [
                'card_category_id' => 'required|numeric|exists:card_categories,id',
                'card_type_name' => 'required|max:45',
                'card_type_name_bn' => 'sometimes|max:45',
                'is_active' => 'required|boolean|max:1|min:0',
            ]);
            if ($validated->fails()) {
                return sendJson('validation fails', $validated->errors(), 422);
            }
            try {
                DB::beginTransaction();
                $cardType->update([
                    'card_category_id' => $request->card_category_id,
                    'card_type_name' => $request->card_type_name,
                    'card_type_name_bn' => $request->card_type_name_bn,
                    'is_active' => $request->is_active,
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                ]);
                DB::commit();
                return sendJson('card type update success', $cardType, 200);
            } catch (Throwable $th) {
                DB::rollBack();
                return sendJson('card type update success', $th->getMessage(), 200);
            }
        } else {
            return sendJson('card type not found', null, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cardType = CardType::find($id);
        if ($cardType) {
            try {
                $cardType->delete();
                return sendJson('card type delete success', null, 200);
            } catch (\Throwable $th) {
                return sendJson('card type delete failed', $th->getMessage(), 200);
            }
        } else {
            return sendJson('card type not found', null, 200);
        }
    }
}
