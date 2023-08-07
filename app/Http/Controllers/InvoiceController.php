<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class InvoiceController extends Controller
{
    public function issueRm($id){
        $data = Http::get('http://45.94.209.231/chiklee_new/chiklee_api/public/api/issueReadOne/'.$id)->json();
        // dd($data['orderItem']);
        // $data['indentList'] = Http::get(Config::get('apis.indentItem.readAll'))->json();
        return view('IssueRMInvoice', $data);
    }
}
