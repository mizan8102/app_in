<!DOCTYPE html>
<html>
<head>
    <title>IOC Report</title>
</head>
<style type="text/css">
    @page {
            header: page-header;
            footer: page-footer;
            /* margin-top: 20mm;   */
            margin-bottom: 30mm;
        }
    body{
        font-family: 'Nikosh';
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
    .head-middle{
        font-size:10px;
        color:red;
        margin-top:20px;
        margin-bottom:10px;
    }
    .logo{
        width: 275px;
        margin-top: 10px;
    }
    .logozit{
        width: 55px;
    }
    .column1{
        float: right;
        width: 20%;
        margin-top:-220px;
    }
    .columne{
        float: right;
        width: 50%;
        margin-top:-100px;
        margin-left: 180px;
    }
    .column5{
        float: right;
        width: 50%;
        margin-top:-70px;
    }
    .column1{
        float: left;
        width: 20%;
        padding-top: 10px;
    }
    .column2 {
        float: left;
        width: 66.66%;
    }
    .column3{
        float: left;
        width: 59%;
        padding-top: 10px;
    }
    .column4{
        float: left;
        width: 80%;
    }
    .columncode{
        float: left;
        width: 20%;
        padding-top:-30px;
    }
    .columncodezit{
        float: left;
        width: 6%;
        padding-bottom:-10px;    
    }
    .row:after {
        content: "";
        display: table;
        clear: both;
    }
    .right_head{
        text-align: center;
        padding: 5px;
        font-weight: bold;
        margin-top: 75px;
    }
    .sr_no{
        width: 3%;
    }
    .logo{
        width: 275px;
        margin-top: 5px;
        text-align:right;
    }
    .head_font{
        font-size: 13px;
        line-height: 13px;
    }
    .head_middle{
        text-align: center;
    }
    .columnzit{
        float: right;
        width: 20%;
        height: 2rem;
        padding-bottom:50px;
    }
    .columnmid{
        float: left;
        width: 20%;
        padding-top: -12.5px;
    }
    .column1{
        float: left;
        width: 20%;
        height: 2rem;
        padding-top: 10px;
    }
    .footer{
            margin-top: 300px;
    }
    .sig{
        display: flex;
        float: left;
        width: 33%;
        text-align: center;
    }
    .col-md-6
        {
            padding-bottom:70px;
        }
        .col-md-5
        {
            padding-bottom: -50px;
        }
        .abc{
           
           width: 45%;
           float: right !important; 
           margin-top: 10px; 

       }
</style>
<body>
    <div class="row" style="none">
        <div class="column1">
            <img class="logo"  src="{{ asset('/logo/logo.png')}}" alt="">
        </div>
        <div class="column3">
            <div class="head_middle">
                <div  style="text-align: center; padding-top:-15px;">
                    <p style="font-size: 18pt;">
                        <strong>Chiklee Water Park</strong>
                    </p>
                </div>
                <div style="text-align: center; padding-top:-45px;">
                    <p style="font-size: 12pt;"> IOC Report<br>
                    </p>
                </div>
            </div>
        </div>
            <div class="column1">
                <p style="text-align: right; font-size:12px;">
                    Report # C_01A <br>
                </p>
            </div>
        </div>
        <div style="margin-left:5px; margin-top:20px; display:;">
            
           
           
            <div class="report_params_areas" style="float:left;height:20px;width:50%">
                <span style="text-align: right; font-size:12px"> Item Name: {{ $data['iocMaster']->iocFGItemName }}</span>
            </div>
            <div class="report_params_areas" style="float:right;height:20px;width:45%;text-align:right; margin-right:5px;">
                <span style="text-align: right; font-size:12px">Print: {{ Date('d-m-Y h:i:s A')}}</span>
            </div>
            <div class="report_params_areas" style="float:left;height:20px;width:100%">
                <span style="text-align: right; font-size:12px">Effective Date:  {{Date('d-m-Y',strtotime($data['iocMaster']->effectiveDate))}}</span>
                <br>
            </div>
            <div class="report_params_areas" style="float:left;height:20px;width:100%">
                <span style="text-align: right; font-size:12px"> IOC No: {{ $data['iocMaster']->iocNo }}</span>
                <br>
            </div>
            <div class="report_params_areas" style="float:left;height:20px;width:100%">
                <span style="text-align: right; font-size:12px"> IOC Qty: {{ $data['iocMaster']->ioc_qty }}</span>
                <br>
            </div>
            <div class="report_params_areas" style="float:left;height:20px;width:100%">
                <span style="text-align: right; font-size:12px"> Cal Qty: {{ $data['iocMaster']->quantity }}</span>
                <br>
            </div>
            
        </div>
        <div style="display:block;">
        <br>
            <div class="data-area">
                <p style="font-size:15px;text-align:center;margin:0px">IOC Details</p>
                <table width="100%" style="display:block; margin-top: 0px;">
                    <thead>
                        <tr style="background-color: rgb(3,73,91);">
                            <th style="text-align: center; color: rgb(255,255,255);">SL</th>
                            <th style="text-align: center; color: rgb(255,255,255);">Item Name</th>
                            <th style="text-align: center; color: rgb(255,255,255);">UOM</th>
                            <th style="text-align: center; color: rgb(255,255,255);">Qty</th>
                            <th style="text-align: center; color: rgb(255,255,255);">IOC Qty</th>
                            <th style="text-align: center; color: rgb(255,255,255);">IOC Rate</th>
                            <th style="text-align: center; color: rgb(255,255,255);">IOC Cost</th>
                            <th style="text-align: center; color: rgb(255,255,255);">Calculation Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $a = 1;
                            $b = 1;
                            $c = 1;
                            $totalConmpQty = 0;
                            $totalConmSingleUnit = 0;
                            $totalcost = 0;
                            $grnadTotalcost = 0;
                            $totalInputSCVAmount = 0;
                            $totalInputSCVSUAmount = 0;
                            $totalVASAmount = 0;
                            $totalVASSUAmount = 0;
                        @endphp
                        @foreach($data['iocDetail'] as $d=>$detail)
                            <tr>
                                <td style="text-align: center;">{{$a}}</td>
                                <td style="text-align: left;" >{{ $detail->itemName }}</td>
                                <td style="text-align: center;" >{{ $detail->uom_short_code }} </td>
                                <td style="text-align: center;" >{{ $detail->consumption }} </td>
                                <td style="text-align: center;" >{{ $detail->consumption_single_unit }} </td>
                                <td style="text-align: right;" >{{ $detail->iocAmount  }} </td>
                                <td style="text-align: right;" >{{ $detail->iocAmount  }} </td>
                                <td style="text-align: right;" >{{ $detail->totalAmount }} </td>
                            </tr>
                            @php
                                $totalConmpQty += $detail->consumption;
                                $totalConmSingleUnit += $detail->consumption_single_unit;
                                $totalcost += $detail->iocAmount;
                                $grnadTotalcost += $detail->totalAmount;
                                
                                $a++;
                            @endphp
                        @endforeach
                            <tr>
                                <td style="text-align: center;" colspan="3">Total</td>
                                <td style="text-align: center;" ></td>
                                <td style="text-align: center;" ></td>
                                <td style="text-align: right;" >{{ number_format($totalcost,2) }} </td>
                                <td style="text-align: right;" >{{ number_format($grnadTotalcost,2) }} </td>
                            </tr>
                    </tbody>
                </table>
                <!-- <p style="font-size:15px;text-align:center;margin:0px">Input Service</p>
                <table width="100%" style="display:block; margin-top: 0px;">
                    <thead>
                        <tr style="background-color: rgb(3,73,91);">
                            <th style="text-align: center; color: rgb(255,255,255);">SL</th>
                            <th style="text-align: center; color: rgb(255,255,255);">Descreption</th>
                            <th style="text-align: center; color: rgb(255,255,255);">Descreption Bn</th>
                            <th style="text-align: center; color: rgb(255,255,255);">IOC Amount</th>
                            <th style="text-align: center; color: rgb(255,255,255);">Amount</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['iocInputService'] as $i=>$is)
                            <tr>
                                <td style="text-align: center;">{{$b}}</td>
                                <td style="text-align: left;">{{ $is->itemName }}</td>
                                <td style="text-align: left;font-size:14px;">{{ $is->itemNameBn }} </td>
                                <td style="text-align: right;">{{ number_format($is->iocAmount,2) }} </td>
                                <td style="text-align: right;">{{ number_format($is->totalAmount,2) }} </td>
                            </tr>
                            @php
                                $totalInputSCVAmount += $is->iocAmount;
                                $totalInputSCVSUAmount += $is->totalAmount;
                                $b++;
                            @endphp
                        @endforeach
                            <tr>
                                <td style="text-align: center;" colspan="3">Total</td>
                                <td style="text-align: right;" >{{ number_format($totalInputSCVAmount,2) }} </td>
                                <td style="text-align: right;" >{{ number_format($totalInputSCVSUAmount,2) }} </td>
                            </tr>
                    </tbody>
                </table>
                <p style="font-size:15px;text-align:center;margin:0px">Value Added Service</p>
                <table width="100%" style="display:block; margin-top: 0px;">
                    <thead>
                        <tr style="background-color: rgb(3,73,91);">
                            <th style="text-align: center; color: rgb(255,255,255);">SL</th>
                            <th style="text-align: center; color: rgb(255,255,255);">Descreption</th>
                            <th style="text-align: center; color: rgb(255,255,255);">Descreption Bn</th>
                            <th style="text-align: center; color: rgb(255,255,255);">IOC Amount</th>
                            <th style="text-align: center; color: rgb(255,255,255);">Amount</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['iocVas'] as $i=>$is)
                            <tr>
                                <td style="text-align: center;" >{{$c}}</td>
                                <td style="text-align: left;" >{{ $is->itemName }}</td>
                                <td style="text-align: left;font-size:14px;">{{ $is->itemNameBn }} </td>
                                <td style="text-align: right;">{{ number_format($is->iocAmount,2) }} </td>
                                <td style="text-align: right;">{{ number_format($is->totalAmount,2) }} </td>
                               
                            </tr>
                            @php
                                $totalVASAmount += $is->iocAmount;
                                $totalVASSUAmount += $is->totalAmount;
                                $c++;
                            @endphp
                        @endforeach
                            <tr>
                                <td style="text-align: center;" colspan="3">Total</td>
                                <td style="text-align: right;" >{{ number_format($totalVASSUAmount,2) }} </td>
                                <td style="text-align: right;" >{{ number_format($totalVASAmount,2) }} </td>
                                
                            </tr>
                    </tbody>
                </table> -->
            </div>

            <div class="abc">
                <!-- <div width="60%">sdfsd</div> -->
                <table width="100%">
                    <tbody>
                            <tr width="100%">
                                <td style="text-align: left;" >Total RM Cost:</td>
                                <td style="text-align: right;" >{{number_format($grnadTotalcost,2)}}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;" >Total Input Service Cost:</td>
                                <td style="text-align: right;" >{{number_format($totalInputSCVAmount,2)}}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;" >Total VAS Cost:</td>
                                <td style="text-align: right;" >{{number_format($totalVASAmount,2)}}</td>
                            </tr>
                            <tr>
                                <td style="text-align: right;" >Grand Total Cost:</td>
                                <td style="text-align: right;" >{{number_format($grnadTotalcost+$totalInputSCVAmount+$totalVASAmount,2)}}</td>
                            </tr>
                    </tbody>
                </table>
            </div> 
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
    </div>
</html>