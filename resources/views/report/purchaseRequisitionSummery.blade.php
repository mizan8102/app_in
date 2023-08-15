<!DOCTYPE html>
<html>
<head>
    <title>Purchase Requisition Summary</title>
</head>
<style type="text/css">
    @page {
            header: page-header;
            footer: page-footer;
            margin-bottom: 30mm;
        }
    body{
        font-family: 'Times New Roman', Times, serif;
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
    .head-middle{
        font-size:10px;
        color:red;
        margin-top:20px;
        margin-bottom:10px;
    }
    .logo{
        width: 275px;
        /*margin-left: 365px;*/
        margin-top: 10px;
    }
    .logozit{
        width: 55px;
        /*margin-left: 365px;*/
        /* margin-top: 10px; */
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
/* Clear floats after the columns */
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
        /*margin-left: 365px;*/
        margin-top: 5px;
        text-align:right;
    }
    .head_font{
        font-size: 13px;
        line-height: 13px;
    }
    .head_middle{
        text-align: center;
        /* font-size: 12px; */
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
        background-size: cover; /* Adjust this based on your needs */
        background-repeat: no-repeat;
    }
    .logo{
        width:100px;
        margin-top: 5px;
        text-align:right;
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
</style>
<body>
    <div class="row" style="none">
        <div class="column1">
            <img class="logo" src="{{ asset('./logo/logo.png')}}" alt="">
        </div>
        <div class="column3">
            <div class="head_middle">
                <div  style="text-align: center; padding-top:-15px;">
                    <p style="font-size: 18pt;">
                        <strong>Chiklee Water Park</strong>
                    </p>
                </div>
                <div style="text-align: center; padding-top:-45px;">
                    <p style="font-size: 12pt;">Purchase Requisition Summary<br>
                    </p>
                </div>
            </div>
        </div>
        <div class="column1">
            <p style="text-align: right; font-size:12px;">
                Report # C_B_03B <br>
            </p>
        </div>
        </div>
        <div style="margin-left:5px; margin-top:20px; display:block;">
            <div class="report_params_areas" style="float:left;height:20px;width:50%">
                <span style="text-align: right; font-size:12px"><b> From Date: </b>{{Date('d-m-Y',strtotime(request('fromDate')) ??'')}}</span>
            </div>
            <div class="report_params_areas" style="float:right;height:20px;width:45%;text-align:right;margin-right:5px;">
                <span style="text-align: right; font-size:12px"><strong>Print: </strong>{{ Date('d-m-Y h:i:s A')}}</span>
            </div>
            <div class="report_params_areas" style="float:left;height:20px;width:100%">
                <span style="text-align: right; font-size:12px"><b> To Date: </b>{{Date('d-m-Y',strtotime(request('toDate')) ??'')}}</span>
                <br>
            </div>
        </div>
        <div style="display:block;">
        <table width="100%" style="display:block; margin-top: 10px;">
            <thead>
                <tr style="background-color: rgb(3,73,91);">
                    <th style="text-align: center; color: rgb(255,255,255);">SL</th>
                    <th style="text-align: center; color: rgb(255,255,255);">No</th>
                    <th style="text-align: center; color: rgb(255,255,255);">Date</th>
                    <th style="text-align: center; color: rgb(255,255,255);">By</th>
                    <th style="text-align: center; color: rgb(255,255,255);">Product Type</th>
                    <th style="text-align: center; color: rgb(255,255,255);">Close Status</th>
                    <th style="text-align: center; color: rgb(255,255,255);">Approved By</th>
                    <th style="text-align: center; color: rgb(255,255,255);">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @if ($pReqSum)
                @foreach($pReqSum as $key=>$data)
                    <tr>
                        <td style="text-align: center;">{{$key+1 ??''}}</td>
                        <td style="text-align: center;">{{$data->prchReqNo ??''}}</td>
                        <td style="text-align: center;">{{isset($data->purchaseDate) ? date('d-m-Y', strtotime($data->purchaseDate)) : ''}}</td>
                        <td style="text-align: left;">{{$data->prchReqByName ??''}}</td>
                        <td style="text-align: left;">{{$data->prodTypeName ??''}}</td>
                        <td style="text-align: center;">{{$data->closeStatus == 0 ? 'Open':'Closed' ??''}}</td>
                        <td style="text-align: left;">{{$data->approvedByName ??''}}</td>
                        <td style="text-align: left;">{{$data->remarks ??''}}</td>
                    </tr>
                @endforeach                     
                @endif
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
    </div>
</html>