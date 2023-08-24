<!DOCTYPE html>

<html>

<head>

    <title>Daily Sells Summary Cash Receive</title>

</head>

<style type="text/css">

    @page {
            header: page-header;
            footer: page-footer;
            margin-bottom: 30mm;

        }

    body{

        font-family: "Nikosh";

    }

    .m-0{

        margin: 0px;

    }

    .p-0{

        padding: 20px;

    }

    .pt-5{

        padding-top:5px;

    }

    .mt-10{

        margin-top:10px;

    }

    .text-center{

        text-align:center !important;

    }

    .w-100{

        width: 100%;

    }

    .w-50{

        width:50%;

    }

    .w-85{

        width:85%;

    }

    .w-15{

        width:15%;

    }

    .logo2 img{

        width:65px;

        height:65px;

        padding-top:30px;

    }
    .logo span{

        margin-left:8px;

        top:19px;

        position: absolute;

        font-weight: bold;

        font-size:25px;

    }


    .gray-color{

        color:#5D5D5D;

    }

    .text-bold{

        font-weight: bold;

    }

    .border{

        border:1px solid black;

    }

    table tr,th,td{

        border: 1px solid black;

        border-collapse:collapse;

        padding:4px 8px;

    }

    table tr th{

        /* background: #F4F4F4; */

        font-size:9px;
        scale: 2;

    }

    table tr td{

        font-size:11px;
        padding:3px 3px;

    }

    table{

        border-collapse:collapse;
        /* float:right; */
        margin-right:5px;
        margin-left:5px;
        margin-bottom:10px;

    }

    .box-text p{

        line-height:10px;

    }

    .float-left{

        float:left;

    }

    .float-left{

        float:right;

    }

    .total-part{

        font-size:16px;

        line-height:12px;

    }

    .total-right p{

        padding-right:20px;

    }
    .head-middle
    {
        font-size:10px;
        color:red;
        margin-top:20px;
        margin-bottom:10px;
    }
    .logo
        {
            width: 275px;
            /*margin-left: 365px;*/
            margin-top: 10px;
        }

        .logozit
        {
            width: 55px;
            /*margin-left: 365px;*/
            /* margin-top: 10px; */
        }
    .column1
        {
            float: right;
            width: 20%;
            margin-top:-220px;
        }
    .columne
        {
            float: right;
            width: 50%;
            margin-top:-100px;
            margin-left: 180px;
        }
    .column5
        {
            float: right;
            width: 50%;
            margin-top:-70px;
        }

        .column1
        {
            float: left;
            width: 20%;
            padding-top: 10px;
        }
        .column2 {
            float: left;
            width: 66.66%;
        }
        .column3
        {
            float: left;
            width: 59%;
            padding-top: 10px;
        }
        .column4
        {
            float: left;
            width: 80%;
        }
        .columncode
        {
            float: left;
            width: 20%;
            padding-top:-30px;
        }
        .columncodezit
        {
            float: left;
            width: 6%;
            padding-bottom:-10px;

        }
        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }
        .right_head
        {
            text-align: center;
            padding: 5px;
            font-weight: bold;
            margin-top: 75px;
        }
        .sr_no
        {
            width: 3%;
        }
        .logo
        {
            width: 275px;
            /*margin-left: 365px;*/
            margin-top: 5px;
            text-align:right;
        }
        .head_font
        {
            font-size: 13px;
            line-height: 13px;
        }
        .head_middle
        {
            text-align: center;
        }
        /* .columnzit
        {
            float: left;
            width: 30%;
            height: 2rem;
            padding-bottom:50px;
        } */

        /* .columnmid
        {
            float: left;
            width: 40%;
            padding-top: -12.5px;
        } */
        .columnmid
        {
            float: left;
            width: 20%;
            padding-top: -23px;
        }
        .columnzit
        {
            float: right;
            width: 15%;
            height: 2rem;
            padding-bottom:40px;
        }
        .column1
        {
            float: left;
            width: 20%;
            height: 2rem;
            padding-top: 10px;
        }
        .col-md-6
        {
            padding-bottom:70px;
        }
        .col-md-5
        {
            padding-bottom: -50px;
        }
</style>

    <body>
        <div class="row" style="none">
            <div class="column1">
                <!-- <img class="logo" src="https://www.pinclipart.com/picdir/middle/187-1872894_bangladesh-govt-logo-png-clipart.png" alt=""> -->
                <img class="logo" style="width:80px;height:60px;" src="{{ asset('logo/logo.png')}}" alt="">
            </div>
            <div class="column3">
                <div class="head_middle">
                    <div  style="text-align: center; padding-top:-8px;">
                        <p style="font-size: 14pt;">
                            <strong>Chiklee Water Park</strong>
                        </p>
                    </div>
                    <div style="text-align: center; padding-top:-25px; font-size: 9pt;">
                        <p>Daily Sells Summary Cash Receive<br>
                        <span>By Sales Point</span>
                        </p>
                    </div>
                </div>
            </div>
            <!-- <div class="column1">
                <p style="text-align: right; font-size:12px;">Chiklee#D_01C</p>
                
            </div> -->
            <div style="display:block; margin-bottom:none!important;">
            <p style="text-align: right; font-size:12px;">Chiklee#D_01C</p>
                <table style= "margin-left: 0px;" width="100%">
                    <thead style="border:none;">
                        <tr style="background-color: rgb(3,73,91);font-size:9px">
                            <th style="text-align: center; color: rgb(255,255,255);">Name</th>
                            <th style="text-align: center; color: rgb(255,255,255);">Quantity</th>
                            <th style="text-align: center; color: rgb(255,255,255);">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="text-align: left;">Entry Ticket Day</td>
                            <td style="text-align: right;">{{ number_format($day_ticket,0) }}</td>
                            <td style="text-align: right;" rowspan="3">{{ intval($day_ticket)+intval($night_ticket)}}</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Entry Ticket Evening</td>
                            <td style="text-align: right;">{{ number_format($night_ticket,0) }}</td>
                        </tr>
                    </tbody>
                </table>
        </div>
        </div>
            <div style="margin-left:5px; margin-top:5px; margin-bottom:4px; display:block;">

                <div class="report_params_areas" style="float:left;height:10px;width:50%">
                    <span style="text-align: right; font-size:12px"><b>Date: </b>{{ date('d-m-Y',strtotime($date))}}</span>
                </div>
                <div class="report_params_areas" style="float:right;height:10px;width:45%;text-align:right;margin-right:5px;">
                    <span style="text-align: right; font-size:12px"><strong>Print: </strong>{{ Date('d-m-Y h:i:s A')}}</span>
                </div>
                <!-- <div class="report_params_areas" style="float:left;height:20px;width:100%">
                    <span style="text-align: right; font-size:12px"><b> Entry Ticket - Day: </b></span>
                    <br>
                </div>
                <div class="report_params_areas" style="float:left;height:20px;width:100%">
                    <span style="text-align: right; font-size:12px"><b> Entry Ticket - Evening: </b></span>
                    <br>
                </div> -->
            </div>
        <div>
        <div style="display:block;">
            <table width="100%">
                <thead>
                    <tr style="background-color: rgb(3,73,91); font-size:9px !important;transform: scale(1.2);">
                        <th style="text-align: center; color: rgb(255,255,255);">SL</th>
                        <th style="text-align: center; color: rgb(255,255,255);" width="13%">Sales Point</th>
                        <th style="text-align: center; color: rgb(255,255,255);">Total Sells</th>
                        <th style="text-align: center; color: rgb(255,255,255);">Discount</th>
                        <th style="text-align: center; color: rgb(255,255,255);">VAT</th>
                        <th style="text-align: center; color: rgb(255,255,255);">Receivables</th>
                        <th style="text-align: center; color: rgb(255,255,255);font-size:8px;">Paid by Customer</th>
                        <th style="text-align: center; color: rgb(255,255,255);">Due to Customer</th>
                        
                        <th style="text-align: center; color: rgb(255,255,255);">Bank Pmt</th>
                        <th style="text-align: center; color: rgb(255,255,255);">Card Pmt</th>
                        <th style="text-align: center; color: rgb(255,255,255);">MFS</th>
                        <th style="text-align: center; color: rgb(255,255,255);" width="8%">Grand Total</th>
                        <th style="text-align: center; color: rgb(255,255,255);">Cash Received</th>
                        <th style="text-align: center; color: rgb(255,255,255);font-size:8px">Due to sales point</th>
                        <th style="text-align: center; color: rgb(255,255,255);">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                @php $due_amt=0;$receive=0; $due_to_cus=0; $grand_total_amt=0;$totalDue=0; @endphp
                @foreach($data as $key=>$dd)

                    <tr>
                        <td style="text-align: right;">{{ ++$key }}</td>
                        <td style="text-align: left;">{{ $dd->str_name }}</td>
                        <td style="text-align: right;">{{$total=number_format($dd->total_issue_amount_with_vat, 2)}}</td>
                        <td style="text-align: right;">{{ number_format($dd->total_discount)}}</td>
                        <td style="text-align: right;">{{$vat=number_format($dd->total_vat_amnt,2) }}</td>
                        <td style="text-align: right;">{{ $rec=number_format($dd->total_issue_amount_with_vat-$dd->total_vat_amnt-$dd->total_discount,2) }}</td>
                        <td style="text-align: right;">{{ number_format($dd->paid_amount,2) }}</td>
                        <td style="text-align: right;">{{$due_customer=number_format(($dd->total_issue_amount_with_vat-$dd->total_vat_amnt)-$dd->paid_amount,2)}}</td>
                      
                        <td style="text-align: right;">
                            {{ number_format($dd->cash_diposit,2)}}
                        </td>
                        <td style="text-align: right;">
                        {{ number_format($dd->card_diposit,2)}}
                        </td>
                        
                        <td style="text-align: right;">
                        {{ number_format($dd->mfs_diposit,2)}}
                        </td>
                        <td style="text-align: right;">
                        {{ $grand_total=number_format(($dd->paid_amount+$dd->cash_diposit+$dd->card_diposit+$dd->mfs_diposit),2)}}
                        </td>
                        <td style="text-align: right;">{{number_format($dd->total_deposit,2)}}</td>
                        <td style="text-align: right;">{{ $due=number_format(doubleval(($dd->paid_amount+$dd->cash_diposit+$dd->card_diposit+$dd->mfs_diposit)) - doubleval($dd->total_deposit) ,2)}}</td>
                        <td style="text-align: right;"></td>
                        @php
                            $due_amt += doubleval($dd->total_issue_amount_with_vat) - doubleval($dd->paid_amount);
                            $receive +=doubleval($dd->total_issue_amount_with_vat-$dd->total_vat_amnt-$dd->total_discount);
                            $due_to_cus +=($dd->total_issue_amount_with_vat-$dd->total_vat_amnt)-$dd->paid_amount;
                            $grand_total_amt +=doubleval($dd->paid_amount+$dd->cash_diposit+$dd->card_diposit+$dd->mfs_diposit);
                            $totalDue +=doubleval(($dd->paid_amount+$dd->cash_diposit+$dd->card_diposit+$dd->mfs_diposit)) - doubleval($dd->total_deposit);
                        @endphp
                    </tr> @endforeach

                    <tr style="border-bottom: 1px solid black;">
                        <td style="text-align: right;"colspan ="2"><strong>Total: </strong></td>
                        <td style="text-align: right;"><strong>{{number_format(collect($data)->sum('total_issue_amount_with_vat'),2)}}</strong></td>
                        <td style="text-align: right;"><strong>{{number_format(collect($data)->sum('total_discount'),2)}}</strong></td>
                        <td style="text-align: right;"><strong>{{number_format(collect($data)->sum('total_vat_amnt'),2)}}</strong></td>
                        <td style="text-align: right;"><strong>{{ number_format($receive,2) }}</strong></td>
                        <td style="text-align: right;"><strong>{{number_format(collect($data)->sum('paid_amount'),2)}}</strong></td>
                        <td style="text-align: right;"><strong>
                            {{number_format($due_to_cus,2)}}
                        </strong></td>
                        <td style="text-align: right;"><strong>
                        {{number_format(collect($data)->sum('cash_diposit'),2)}}
                        </strong></td>
                        <td style="text-align: right;"><strong>
                        {{number_format(collect($data)->sum('card_diposit'),2)}}
                        </strong></td>
                        <td style="text-align: right;"><strong>
                        {{number_format(collect($data)->sum('mfs_diposit'),2)}}
                        </strong></td>
                        <td style="text-align: right;"><strong>{{ number_format($grand_total_amt,2)}}</strong></td>
                        <td style="text-align: right;"><strong>{{number_format(collect($data)->sum('total_deposit'),2)}} </strong></td>
                        <td style="text-align: right;"><strong>{{ number_format($totalDue,2) }}</strong></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <htmlpagefooter name="page-footer">
            <div class="col-md-5">
                <p style="text-align: left;font-size: 12px;">{PAGENO} of {nbpg} pages</p>
            </div>
            <div class="col-md-6">
                <p style="text-align: right; font-size: 14px;"><strong>Chiklee </strong><br>
                    <span style="font-size: 12px;">A product of</span><span style="font-size:14px;"><strong> Z IT</strong></span>
                </p>
            </div>
        </htmlpagefooter>
    </html>
