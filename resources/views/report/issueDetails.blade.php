<!DOCTYPE html>
<html>

<head>
    <title>Issue Details</title>
</head>
<style type="text/css">
    @page {
        header: page-header;
        footer: page-footer;
    }

    body {
        font-family: 'Times New Roman', Times, serif;
    }

    .m-0 {
        margin: 0px;
    }

    .p-0 {
        padding: 20px;
    }

    .pt-5 {
        padding-top: 5px;
    }

    .mt-10 {
        margin-top: 10px;
    }

    .text-center {
        text-align: center !important;
    }

    .w-100 {
        width: 100%;
    }

    .w-50 {
        width: 50%;
    }

    .w-85 {
        width: 85%;
    }

    .w-15 {
        width: 15%;
    }

    .logo2 img {
        width: 75px;
        height: 75px;
        padding-top: 30px;
    }

    .logo span {
        margin-left: 8px;
        top: 19px;
        position: absolute;
        font-weight: bold;
        font-size: 25px;
    }

    .gray-color {
        color: #5D5D5D;
    }

    .text-bold {
        font-weight: bold;
    }

    .border {
        border: 1px solid black;
    }

    table tr,
    th,
    td {
        border: 1px solid black;
        border-collapse: collapse;
        padding: 7px 8px;
    }

    table tr th {
        /* background: #F4F4F4; */
        font-size: 12px;
    }

    table tr td {
        font-size: 11px;
        padding: 3px 3px;
    }

    table {
        border-collapse: collapse;
        float: left;
        margin-right: 5px;
        margin-left: 5px;
        margin-bottom: 40px;
    }

    .box-text p {
        line-height: 10px;
    }

    .float-left {
        float: left;
    }

    .float-left {
        float: right;
    }

    .total-part {
        font-size: 16px;
        line-height: 12px;
    }

    .total-right p {
        padding-right: 20px;
    }

    .head-middle {
        font-size: 10px;
        color: red;
        margin-top: 20px;
        margin-bottom: 10px;
    }

    .logo {
        width: 275px;
        /*margin-left: 365px;*/
        margin-top: 10px;
    }

    .logozit {
        width: 55px;
        /*margin-left: 365px;*/
        /* margin-top: 10px; */
    }

    .column1 {
        float: right;
        width: 20%;
        margin-top: -220px;
    }

    .columne {
        float: right;
        width: 40%;
        margin-top: -100px;
        margin-left: 380px;
    }

    .column5 {
        float: right;
        width: 50%;
        margin-top: -70px;
    }

    .column1 {
        float: left;
        width: 20%;
        padding-top: 10px;
    }

    .column2 {
        float: left;
        width: 66.66%;
    }

    .column3 {
        float: left;
        width: 59%;
        padding-top: 10px;
    }

    .column4 {
        float: left;
        width: 80%;
    }

    .columncode {
        float: left;
        width: 20%;
        padding-top: -30px;
    }

    .columncodezit {
        float: left;
        width: 6%;
        padding-bottom: -10px;
    }

    /* Clear floats after the columns */
    .row:after {
        content: "";
        display: table;
        clear: both;
    }

    .right_head {
        text-align: center;
        padding: 5px;
        font-weight: bold;
        margin-top: 75px;
    }

    .sr_no {
        width: 3%;
    }

    .logo {
        width: 275px;
        /*margin-left: 365px;*/
        margin-top: 5px;
        text-align: right;
    }

    .head_font {
        font-size: 13px;
        line-height: 13px;
    }

    .head_middle {
        text-align: center;
        /* font-size: 12px; */
    }

    .columnzit {
        float: right;
        width: 20%;
        height: 2rem;
        padding-bottom: 50px;
    }

    .columnmid {
        float: left;
        width: 20%;
        padding-top: -12.5px;
    }

    .column1 {
        float: left;
        width: 20%;
        height: 2rem;
        padding-top: 10px;
    }

    .footer {
        margin-top: 300px;
    }

    .sig {
        display: flex;
        float: left;
        width: 33%;
        text-align: center;
    }

    .box {
        float: left;
        margin-top: 40px;
        width: 55%;
        height: 100px;
        border: 1px solid #121212;
        padding: 5px;
    }
</style>

<body>
    <div class="row" style="none">
        <div class="column1">
            <!-- <img class="logo" src="https://www.pinclipart.com/picdir/middle/187-1872894_bangladesh-govt-logo-png-clipart.png" alt=""> -->
            <img class="logo" src="http://chiklee-park.com/assets/img/2022-01-04/A2.png" alt="">
        </div>
        <div class="column3">
            <div class="head_middle">
                <div style="text-align: center; padding-top:-15px;">
                    <p style="font-size: 18pt;">
                        <strong>Chiklee Water Park</strong>
                    </p>
                </div>
                <div style="text-align: center; padding-top:-40px;">
                    <p style="text-decoration:underline; font-size: 14pt;"><b> Issue Details</b><br>
                    </p>
                </div>
            </div>
        </div>
        <div class="column1">
            <p style="text-align: right; font-size:12px;">
                Report # C_C_04A <br>
            </p>
        </div>
    </div>
    <div style="margin-left:3px; margin-top:20px;">
        <p style="text-align: left; font-size: 12px">
            <b>Issuing Store:</b> {{ $issue[0]->issuingStore }}<br>
            <b>Receiving Store: </b><br>
        </p>
        <div class="columne" style="text-align:left; float:left; margin-right:10px; font-size: 12px;">
            <p style="padding-top: 35px;">
                <strong>Issue Date: {{ $issue[0]->issueDate }}</strong><br>
                <span><strong>Indent/Product Requisition No: {{ $issue[0]->indentNumber }}</strong></span> <br>
                <span><strong>Indent/Product Requisition Date: {{ $issue[0]->indentDate }}</strong></span> <br>
                <strong>Print: </strong>
                @php
                $mytime = Carbon\Carbon::now()->format ('d-m-Y h:i A');
                echo $mytime;
                @endphp
            </p>
        </div>
    </div>
    <table width="100%" style="margin-top:30px;">
        <thead>
            <tr style="background-color: rgb(3,73,91);">
                <th style="text-align: center; color: rgb(255,255,255);">SL</th>
                <th style="text-align: center; color: rgb(255,255,255);">Item Name</th>
                <th style="text-align: center; color: rgb(255,255,255);">Qty</th>
                <th style="text-align: center; color: rgb(255,255,255);">UOM</th>
                <th style="text-align: center; color: rgb(255,255,255);">Price(BDT)</th>
                <th style="text-align: center; color: rgb(255,255,255);">Amount(BDT)</th>
                <th style="text-align: center; color: rgb(255,255,255);">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($issue as $key=>$item)
            <tr>
                <td style="text-align: center;">{{ $key+1 }}</td>
                <td style="text-align: left;">{{ $item->itemName }}</td>
                <td style="text-align: center;">{{ $item->qty }}</td>
                <td style="text-align: center;">{{ $item->uom }}</td>
                <td style="text-align: right;">{{number_format($item->rate, 2, '.', ',')}}</td>
                <td style="text-align: right;">{{number_format($item->amount, 2, '.', ',')}}</td>
                <td style="text-align: left;">{{ $item->remarks }} </td>
            </tr>
            @endforeach
            <tr style="border-bottom: 1px solid black;">
                <td style="text-align: right;" colspan="2"><strong>Total: </strong></td>
                @php
                $total = 0;
                $amount = 0;
                foreach ($issue as $item) {
                $total += $item->qty;
                $amount += $item->amount;
                }
                @endphp
                <td style="text-align: center;"><b>{{ $total }}</b></td>
                <td style="text-align: right;" colspan="3"><strong>{{number_format($amount, 2, '.', ',')}}</strong></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <p style="margin-left: 5px;">
        <b>In Words:</b> Three Thousand Taka Only
    </p>


    <div class="footer" style="margin-left: 20px; font-size: x-small;">
        <div class="sig">
            <p>____________________________<br> Submitted By</p>
        </div>
        <div class="sig">
            <p>____________________________<br> Recommended By</p>
        </div>
        <div class="sig">
            <p>____________________________<br> Approved By</p>
        </div>
    </div>
    <htmlpagefooter name="page-footer">
        <div>
            <p><span style="font-size:18px; font-weight:bold;">&nbsp;&nbsp;&nbsp;Chiklee</span>
                <br> <span style="color:gray; font-size:12px;">A product by </span><strong>ZIT</strong>
            <p style="text-align: center; font-size:12px; padding-bottom:50px;">Page no: {PAGENO} </p>
        </div>
        <div class="row" style="none">
            <div class="columnzit">
                <p><span style="font-size: 14px; text-align:center;">
                        <strong>Chiklee </strong></span> <br>
                    <span style="font-size: 12px;">A product of</span><span style="font-size:10px;"><strong>
                            ZIT</strong></span>
                </p>
            </div>
            <div class="columnmid">
                <div class="head_middle">
                    <div style="text-align: center;">
                        <p style="text-align: center; font-size: 12px;">{PAGENO} of {nbpg} pages </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="column1">
        </div>
    </htmlpagefooter>
    </div>

</html>
