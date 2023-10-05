<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use PDF;
class CommercialInvoiceController extends Controller
{
    public function commercialInvoice(Request $request)
    {
        $orderIds=DB::select('CALL GetOrderId');
        $foods = DB::select('CALL GetFoodCharge("'.$request->orderId.'")');
        $commers = DB::select('CALL GetProgramManagementHeader("'.$request->orderId.'")');
        //dd($commers);
        //  $commercials = collect($commers)->groupBy('orderID');
        //dd($commercials);
        $pdf = PDF::loadView('commercial_invoice', compact('commers','orderIds', 'foods'));

        return $pdf->stream('commercial_invoice.pdf');
    }

    public function commercial(Request $request)
        {
            //dd($request->all());
            $orderIds=DB::select('CALL GetOrderId');
            if(isset($request->orderId)){
            $commers = DB::select('CALL Report_A_07_CommercialInvoiceForProgramEvent("'.$request->orderId.'")');
            //dd($commers);
                // $commercials = collect($commers)->groupBy('orderId');
               //dd($commercials);
                return view('commercial_preview',compact('commers', 'orderIds'));
            }else{
                $commers =[];
                return view('commercial_preview',compact('commers', 'orderIds'));
            }

        }





        public function programManagement(Request $request)
        {
            $orderIds=DB::select('CALL GetOrderId');
            $programs = DB::select('CALL Report_A_07_CommercialInvoiceForProgramEvent("'.$request->orderID.'")');
            // dd($programs);
            $data = Db::select('CALL GetPaymentHistory("'.$request->orderID.'")');

            $collection = collect($programs);
            //   dd($collection);
             // program menu


            $menuitem = $collection->where('ItemTypeId', 3);
            $menuprograms = [];
            $m = 0;
            foreach ($menuitem as $item => $key) {
                $menuprograms[$m]['ItemId'] = $key->ItemId;
                $menuprograms[$m]['ItemName'] = $key->ItemName;
                $menuprograms[$m]['UomCode'] = $key->UomCode;
                $menuprograms[$m]['OrderQty'] = $key->OrderQty;
                $menuprograms[$m]['ItemRate'] = $key->ItemRate;
                $menuprograms[$m]['Amount'] = $key->Amount;
                $menuprograms[$m]['Discount'] = $key->Discount;
                $menuprograms[$m]['VatAmount'] = $key->VatAmount;
                $menuprograms[$m]['TotalAmountWithVat'] = $key->TotalAmountWithVat;
                $menuprograms[$m]['TotalAmount'] = $key->TotalAmount;
                $m++;
            }
             // ride
            $ride = $collection->where('MasterGroupId', 15);
            $ridePrograms = [];
            $k = 0;
            foreach ($ride as $item => $key) {
                // $ridePrograms[$k]['id'] = $key->id;
                $ridePrograms[$k]['ItemId'] = $key->ItemId;
                $ridePrograms[$k]['ItemName'] = $key->ItemName;
                $ridePrograms[$k]['UomCode'] = $key->UomCode;
                $ridePrograms[$k]['OrderQty'] = $key->OrderQty;
                $ridePrograms[$k]['ItemRate'] = $key->ItemRate;
                $ridePrograms[$k]['Amount'] = $key->Amount;
                $ridePrograms[$k]['Discount'] = $key->Discount;
                $ridePrograms[$k]['VatAmount'] = $key->VatAmount;
                $ridePrograms[$k]['TotalAmountWithVat'] = $key->TotalAmountWithVat;
                $ridePrograms[$k]['TotalAmount'] = $key->TotalAmount;
                $k++;
            }

            // services
            $service = $collection->where('MasterGroupId', 213);
            $servicePrograms = [];
            $kk = 0;
            foreach ($service as $item => $key) {
                $servicePrograms[$kk]['ItemId'] = $key->ItemId;
                $servicePrograms[$kk]['ItemName'] = $key->ItemName;
                $servicePrograms[$kk]['UomCode'] = $key->UomCode;
                $servicePrograms[$kk]['OrderQty'] = $key->OrderQty;
                $servicePrograms[$kk]['ItemRate'] = $key->ItemRate;
                $servicePrograms[$kk]['Amount'] = $key->Amount;
                $servicePrograms[$kk]['Discount'] = $key->Discount;
                $servicePrograms[$kk]['VatAmount'] = $key->VatAmount;
                $servicePrograms[$kk]['TotalAmountWithVat'] = $key->TotalAmountWithVat;
                $servicePrograms[$kk]['TotalAmount'] = $key->TotalAmount;
                $kk++;
            }
            $result=[
                'parent'=>$programs,
                'menu'=>$menuprograms,
                'ride'=>$ridePrograms,
                'service'=>$servicePrograms,
                'child'=>$data
            ];
            // dd($result);
            $pdf = PDF::loadView('report.commercial_invoice', compact('result', 'orderIds'));

            return $pdf->stream('report.commercial_invoice.pdf');
        }
        public function quotations(Request $request)
        {
            $orderIds=DB::select('CALL GetOrderId');
            $programs = DB::select('CALL Report_A_07_CommercialInvoiceForProgramEvent("'.$request->orderID.'")');
            // dd($programs);

            $collection = collect($programs);
            //   dd($collection);
             // program menu


            $menuitem = $collection->where('ItemTypeId', 3);
            $menuprograms = [];
            $m = 0;
            foreach ($menuitem as $item => $key) {
                $menuprograms[$m]['ItemId'] = $key->ItemId;
                $menuprograms[$m]['ItemName'] = $key->ItemName;
                $menuprograms[$m]['UomCode'] = $key->UomCode;
                $menuprograms[$m]['OrderQty'] = $key->OrderQty;
                $menuprograms[$m]['ItemRate'] = $key->ItemRate;
                $menuprograms[$m]['Amount'] = $key->Amount;
                $menuprograms[$m]['Discount'] = $key->Discount;
                $menuprograms[$m]['VatAmount'] = $key->VatAmount;
                $menuprograms[$m]['TotalAmountWithVat'] = $key->TotalAmountWithVat;
                $menuprograms[$m]['TotalAmount'] = $key->TotalAmount;
                $m++;
            }
             // ride
            $ride = $collection->where('MasterGroupId', 15);
            $ridePrograms = [];
            $k = 0;
            foreach ($ride as $item => $key) {
                // $ridePrograms[$k]['id'] = $key->id;
                $ridePrograms[$k]['ItemId'] = $key->ItemId;
                $ridePrograms[$k]['ItemName'] = $key->ItemName;
                $ridePrograms[$k]['UomCode'] = $key->UomCode;
                $ridePrograms[$k]['OrderQty'] = $key->OrderQty;
                $ridePrograms[$k]['ItemRate'] = $key->ItemRate;
                $ridePrograms[$k]['Amount'] = $key->Amount;
                $ridePrograms[$k]['Discount'] = $key->Discount;
                $ridePrograms[$k]['VatAmount'] = $key->VatAmount;
                $ridePrograms[$k]['TotalAmountWithVat'] = $key->TotalAmountWithVat;
                $ridePrograms[$k]['TotalAmount'] = $key->TotalAmount;
                $k++;
            }

            // services
            $service = $collection->where('MasterGroupId', 213);
            $servicePrograms = [];
            $kk = 0;
            foreach ($service as $item => $key) {
                $servicePrograms[$kk]['ItemId'] = $key->ItemId;
                $servicePrograms[$kk]['ItemName'] = $key->ItemName;
                $servicePrograms[$kk]['UomCode'] = $key->UomCode;
                $servicePrograms[$kk]['OrderQty'] = $key->OrderQty;
                $servicePrograms[$kk]['ItemRate'] = $key->ItemRate;
                $servicePrograms[$kk]['Amount'] = $key->Amount;
                $servicePrograms[$kk]['Discount'] = $key->Discount;
                $servicePrograms[$kk]['VatAmount'] = $key->VatAmount;
                $servicePrograms[$kk]['TotalAmountWithVat'] = $key->TotalAmountWithVat;
                $servicePrograms[$kk]['TotalAmount'] = $key->TotalAmount;
                $kk++;
            }
            $result=[
                'parent'=>$programs,
                'menu'=>$menuprograms,
                'ride'=>$ridePrograms,
                'service'=>$servicePrograms,
                
            ];
            // dd($result);
            $pdf = PDF::loadView('report.quotations', compact('result', 'orderIds'));

            return $pdf->stream('program_quotations.pdf');
        }

        public function program(Request $request)
        {
            //dd($request->all());
            $orderIds=DB::select('CALL GetOrderId');
            if(isset($request->orderID)){
            $programs = DB::select('CALL Report_A_07_CommercialInvoiceForProgramEvent("'.$request->orderID.'")');
           // dd($programs);
            $collection = collect($programs);
            //   dd($collection);
             // program menu


            $menuitem = $collection->where('ItemTypeId', 3);
            $menuprograms = [];
            $m = 0;
            foreach ($menuitem as $item => $key) {
                $menuprograms[$m]['ItemId'] = $key->ItemId;
                $menuprograms[$m]['ItemName'] = $key->ItemName;
                $menuprograms[$m]['UomCode'] = $key->UomCode;
                $menuprograms[$m]['OrderQty'] = $key->OrderQty;
                $menuprograms[$m]['ItemRate'] = $key->ItemRate;
                $menuprograms[$m]['Amount'] = $key->Amount;
                $menuprograms[$m]['Discount'] = $key->Discount;
                $menuprograms[$m]['VatAmount'] = $key->VatAmount;
                $menuprograms[$m]['TotalAmountWithVat'] = $key->TotalAmountWithVat;
                $menuprograms[$m]['TotalAmount'] = $key->TotalAmount;
                $m++;
            }
             // ride
            $ride = $collection->where('MasterGroupId', 15);
            $ridePrograms = [];
            $k = 0;
            foreach ($ride as $item => $key) {
                // $ridePrograms[$k]['id'] = $key->id;
                $ridePrograms[$k]['ItemId'] = $key->ItemId;
                $ridePrograms[$k]['ItemName'] = $key->ItemName;
                $ridePrograms[$k]['UomCode'] = $key->UomCode;
                $ridePrograms[$k]['OrderQty'] = $key->OrderQty;
                $ridePrograms[$k]['ItemRate'] = $key->ItemRate;
                $ridePrograms[$k]['Amount'] = $key->Amount;
                $ridePrograms[$k]['Discount'] = $key->Discount;
                $ridePrograms[$k]['VatAmount'] = $key->VatAmount;
                $ridePrograms[$k]['TotalAmountWithVat'] = $key->TotalAmountWithVat;
                $ridePrograms[$k]['TotalAmount'] = $key->TotalAmount;
                $k++;
            }

            // services
            $service = $collection->where('MasterGroupId', 213);
            $servicePrograms = [];
            $kk = 0;
            foreach ($service as $item => $key) {
                $servicePrograms[$kk]['ItemId'] = $key->ItemId;
                $servicePrograms[$kk]['ItemName'] = $key->ItemName;
                $servicePrograms[$kk]['UomCode'] = $key->UomCode;
                $servicePrograms[$kk]['OrderQty'] = $key->OrderQty;
                $servicePrograms[$kk]['ItemRate'] = $key->ItemRate;
                $servicePrograms[$kk]['Amount'] = $key->Amount;
                $servicePrograms[$kk]['Discount'] = $key->Discount;
                $servicePrograms[$kk]['VatAmount'] = $key->VatAmount;
                $servicePrograms[$kk]['TotalAmountWithVat'] = $key->TotalAmountWithVat;
                $servicePrograms[$kk]['TotalAmount'] = $key->TotalAmount;
                $kk++;
            }
            $result=[
                'parent'=>$programs,
                'menu'=>$menuprograms,
                'ride'=>$ridePrograms,
                'service'=>$servicePrograms
            ];
            //dd($result);
                return view('management_preview',compact('programs', 'orderIds'));
            }else{
                $programs =[];
                return view('management_preview',compact('programs', 'orderIds'));
            }

        }

        public function programManagementpdf(Request $request)
        {
            $invoice = DB::select('CALL Report_B_01B_IndentSummaryReport("'.$request->orderId.'")');
            //dd($invoice);
            $pdf = PDF::loadView('program_management_pdf',
            [
                'mode'                 => '',
                'format'               => 'A4-L',
                'default_font_size'    => '12',
                'default_font'         => 'sans-serif',
                'margin_left'          => 5,
                'margin_right'         => 5,
                'margin_top'           => 25,
                'margin_bottom'        => 15,
                'margin_header'        => 0,
                'margin_footer'        => 0,
                'orientation'          => 'L',
                'title'                => 'Laravel mPDF',
                'author'               => '',
                'watermark'            => '',
                'show_watermark'       => true,
                'watermark_font'       => 'sans-serif',
                'display_mode'         => 'fullpage',
                'watermark_text_alpha' => 0.1,
                'custom_font_dir'      => '',
                'custom_font_data' 	   => [],
                'auto_language_detection'  => false,
                'temp_dir'               => rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR),
                'pdfa' 			=> false,
                'pdfaauto' 		=> false,
            ]
        );

            return $pdf->stream('program_management_pdf.pdf',compact('invoice'));
        }

        public function eventManagement(Request $request)
        {
            $orderIds=DB::select('CALL GetOrderId');
            //dd($orderIds);
            $programs = DB::select('CALL Report_C_B_01A2_EventManagement("'.$request->orderID.'")');

            $events = DB::select('CALL Report_C_B_01A2_IndentMaster("'.$request->orderID.'")');
            //dd($programs);

            $collection = collect($programs);
             //dd($collection);
             // program menu


            $menuitem = $collection->where('ItemTypeId', 3);
            $menuprograms = [];
            $m = 0;
            foreach ($menuitem as $item => $key) {
                $menuprograms[$m]['ItemId'] = $key->ItemId;
                $menuprograms[$m]['ItemName'] = $key->ItemName;
                $menuprograms[$m]['ItemNameBn'] = $key->ItemNameBn;
                $menuprograms[$m]['UomCode'] = $key->UomCode;
                $menuprograms[$m]['OrderQty'] = $key->OrderQty;
                $menuprograms[$m]['ItemRate'] = $key->ItemRate;
                $menuprograms[$m]['Amount'] = $key->Amount;
                $menuprograms[$m]['Discount'] = $key->Discount;
                $menuprograms[$m]['VatAmount'] = $key->VatAmount;
                $menuprograms[$m]['TotalAmountWithVat'] = $key->TotalAmountWithVat;
                $menuprograms[$m]['TotalAmount'] = $key->TotalAmount;
                $m++;
            }
             
            $result=[
                'parent'=>$programs,
                'menu'=>$menuprograms
            ];
            //dd($result);
            $pdf = PDF::loadView('report.event_management', compact('result', 'orderIds', 'events'));

            return $pdf->stream('report.event_management.pdf');
        }
}
