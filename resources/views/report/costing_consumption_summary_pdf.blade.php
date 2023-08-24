<!DOCTYPE html>

<html>

<head>

    <title>Report A_02 Order Wise Daily sell</title>

</head>

<style type="text/css">

    @page {
            header: page-header;
            footer: page-footer;
            margin-bottom: 30mm;
        }

    body{

        font-family: "Nikosh";
        font-family: "Times New Roman";

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

        width:75px;

        height:75px;

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

        padding:7px 8px;

    }

    table tr th{

        /* background: #F4F4F4; */

        font-size:12px;

    }

    table tr td{

        font-size:11px;
        padding:3px 3px;

    }

    table{

        border-collapse:collapse;
        float:left;
        margin-right:5px;
        margin-left:5px;
        margin-bottom:40px;

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
        .columnzit
        {
            float: left;
            width: 30%;
            height: 2rem;
            padding-bottom:50px;
        }
        .columnmid
        {
            float: left;
            width: 40%;
            padding-top: -12.5px;
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
            <img class="logo" src="{{asset('logo/logo.png')}}" alt="no image">
            </div>
            <div class="column3">
                <div class="head_middle">
                    <div  style="text-align: center; padding-top:-15px;">
                        <p style="font-size: 18pt;">
                            <strong>Chiklee Water Park</strong>
                        </p>
                    </div>
                    <div style="text-align: center; padding-top:-40px;">
                        <p>Costing/Consumption Summary<br>
                        </p>
                    </div>
                </div>
            </div>
            <div class="column1">
                <p style="text-align: right; font-size:12px;">Chiklee#C_01B</p>
            </div>
        </div>
            <div style="margin-left:5px; margin-top:20px; display:block;">
                <div class="report_params_areas" style="float:right;height:20px;width:45%;text-align:right;margin-right:5px;">
                  <span style="text-align: right; font-size:12px"><strong>Print: </strong>{{ Date('d-m-Y h:i:s A')}}</span>
                </div>
                <div class="report_params_areas" style="float:left;height:20px;width:100%">
                    <span style="text-align: right; font-size:12px">
                    <strong> Restaurant Name: </strong>{{$orders[0]->sl_name ?? ''}}
                    </span>
                    <br>
                </div>
            </div>
        <div style="display:block; margin-top:10px;">
            <table width="100%">
                <thead>
                    <tr style="background-color: rgb(3,73,91);">
                        <th style="text-align: center; color: rgb(255,255,255);">SL</th>
                        <th style="text-align: center; color: rgb(255,255,255);">IOC No</th>
                        <th style="text-align: center; color: rgb(255,255,255);">Effective Date</th>
                        <th style="text-align: center; color: rgb(255,255,255);">Item Name[F]</th>
                        <th style="text-align: center; color: rgb(255,255,255);">Costing UOM</th>
                        <th style="text-align: center; color: rgb(255,255,255);">Amount(BDT)</th>
                        <th style="text-align: center; color: rgb(255,255,255);">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($orders as $key => $order)
                    <tr>
                        <td style="text-align: center;">{{$key+1}}</td>
                        <td style="text-align: left;">{{$order->iocNo}}</td>
                        <td style="text-align: center;">{{Date('d-m-Y',strtotime($order->effectiveDate))}}</td>
                        <td style="text-align: center;">{{$order->ItemName}}</td>
                        <td style="text-align: center;">{{$order->uom_short_code}}</td>
                        <td style="text-align: right;">{{number_format($order->iocAmount, 2, '.', ',')}}</td>
                        <td style="text-align: left;">{{$order->remarks}}</td>
                    </tr>
                @endforeach
                    <tr style="border-bottom: 1px solid black;">
                        <td style="text-align: right;"colspan ="5"><strong>Sub Total: </strong></td>
                        <td style="text-align: right;"><strong>{{number_format(collect($orders)->sum('iocAmount'),2, '.', ',')}}</strong></td>
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