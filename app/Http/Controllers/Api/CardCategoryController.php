<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\CardCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CardCategoryController extends Controller
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
        $cardCategory = CardCategory::
            // all();
            query()
            ->when($search, function ($query, $search) {
                return $query->where('card_cat_name', 'like', '%' . $search . '%')
                    ->orWhere('card_cat_name_bn', 'like', '%' . $search . '%');
            })
            ->orderBy('card_cat_name', 'ASC')
            ->paginate($perPage);
        return sendJson('list of the card category', $cardCategory, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'card_cat_name' => 'required|max:45',
            'card_cat_name_bn' => 'required|max:45',
            'is_active' => 'required|boolean|max:1|min:0',
        ]);
        try {
            DB::beginTransaction();
            $cardCategory = CardCategory::create([
                'card_cat_name' => $request->card_cat_name,
                'card_cat_name_bn' => $request->card_cat_name_bn,
                'is_active' => $request->is_active,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
            DB::commit();
            return sendJson('Card Category create success', $cardCategory, 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return sendJson('Card Category create failed', $th->getMessage(), 500);
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
        $cardCategory = CardCategory::find($id);
        if ($cardCategory) {
            return sendJson('Category found', $cardCategory, 200);
        } else {
            return sendJson('Category found', null, 200);
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
        $cardCategory = CardCategory::find($id);
        if ($cardCategory) {
            return sendJson('Category found', $cardCategory, 200);
        } else {
            return sendJson('Category found', null, 200);
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
        $cardCategory = CardCategory::find($id);
        if ($cardCategory) {
            try {
                $validated = Validator::make($request->all(), [
                    'card_cat_name' => 'required|max:45',
                    'card_cat_name_bn' => 'required|max:45',
                    'is_active' => 'required|boolean|max:1|min:0',
                ]);
                if ($validated->fails()) {
                    return sendJson('validation fails', $validated->errors(), 422);
                }
                DB::beginTransaction();
                $cardCategory->update([
                    'card_cat_name' => $request->card_cat_name,
                    'card_cat_name_bn' => $request->card_cat_name_bn,
                    'is_active' => $request->is_active,
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                ]);
                DB::commit();
                return sendJson('Card Category update success', $cardCategory, 200);
            } catch (Throwable $th) {
                DB::rollBack();
                return sendJson('Card Category update failed', $th->getMessage(), 400);
            }
        } else {
            return sendJson('Category found', null, 400);
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
        $cardCategory = CardCategory::find($id);
        if ($cardCategory) {
            return sendJson('Category found', $cardCategory, 200);
        } else {
            return sendJson('Category found', null, 200);
        }
    }
}
