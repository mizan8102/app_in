<!DOCTYPE html>

<html>

<head>

    <title>Chiklee</title>

</head>

<style type="text/css">

        @page {
                header: page-header;
                footer: page-footer;
                margin-top: 10mm;
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

        padding:2px 3px;

    }

    table tr th{

        /* background: #F4F4F4; */

        font-size:10px;

    }

    table tr td{

        font-size:10px;
        padding:3px 3px;
        color:

    }

    table{

        border-collapse:collapse;
        float:left;
        margin-right:5px;
        margin-left:5px;
        margin-bottom:5px;


    }

    .box-text p{

        line-height:5px;

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
    .columncall
    {
        float: left;
        width: 18%;
        padding-top: 10px;
    }
    .columnemail
    {
        float: left;
        width: 25%;
        padding-top: 10px;
    }
    .columnworld
    {
        float: left;
        width: 22%;
        padding-top: 10px;
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
    .rowhead{
        background-color: rgb(207, 237, 241);
    }
    .rowfooter{
        background-color: rgb(207, 237, 241);
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
        width: 125px;
        /*margin-left: 365px;*/
        margin-top: 5px;
        text-align:right;
    }
    .chiklee
    {
        width: 125px;
        /*margin-left: 365px;*/
        padding-top: 5px;
        text-align:right;
    }
    .columnfooter
    {
        float: left;
        width: 15%;
        height: .5rem;
        padding-bottom: 5px;
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
        margin-top:70px;
        float: left;
        width: 30%;
        height: 2rem;
        padding-bottom:50px;
    }
    .columnmid
    {
        margin-top:70px;
        float: left;
        width: 40%;
        padding-top: -12.5px;
    }
    .invoice
    {
        margin-top:70px;
        float: left;
        width: 40%;
        height: 2rem;
        padding-top: 10px;
    }
    .column3
    {
        float: left;
        width: 19%;
        padding-top: 10px;
    }
    .qrcode
    {
        width: 100%;
        margin-left: 240px;
        margin-top: 5px;
        text-align: right;
    }
    .footimg
    {
        width: 15px;
        margin-top: 18px;
        color: rgb (255, 255, 102);
    }

    .footer{
        margin-top:70px;
        overflow: auto;

    }


</style>

    <body>
        <div class="rowhead" style="none">
            <div class="invoice">
                <img class="logo" src="{{asset('logo/logo.png')}}" alt="">

                    <P style="margin-left:20px; font-size:12px;"><strong>INVOICE TO<br></p>


                        <p style="font-size:12px; margin-left:18px;">
                           <span style="font-weight:bold;"> {{ $result['parent'][0]->CustomerName }}<br></span>
                            Contact Person Name: {{ $result['parent'][0]->ContactPersonName }}<br>
                            Phone: {{ $result['parent'][0]->CustomerPhone }}<br>
                            Program Name:{{ $result['parent'][0]->ProgramName }}<br>
                            Number of Guests:{{ $result['parent'][0]->NumberOfGuest }}
                        </p>
            </div>
            <div class="column3">
                <div class="head_middle">
                    <h3>QUOTATION</h3>
                </div>
            </div>
            <div class="invoice">
                <img class="qrcode" src="https://cdn-icons-png.flaticon.com/512/241/241521.png" alt="">
                <p style="text-align: right; font-size:12px;"><span><strong>INVOICE <br> No: INV#00{{ request('orderID')}}</strong></p>
                <p style="text-align: right; font-size:12px;">Program Type: {{ $result['parent'][0]->ProgramTypeName }} <br>
                    Program Date:{{ date('d-m-Y',strtotime($result['parent'][0]->ProgramDate))  }} <br>
                    Program Time: {{ $result['parent'][0]->ProgramSessionName }}
                    ({{ $result['parent'][0]->ProgramStartTime }}
                    {{ $result['parent'][0]->ProgramEndTime }})
                    <br>Print: {{ Date('d-m-Y h:i:s A')}}</span>
                </p>
            </div>
        </div>
        <div style="margin-left:3px; margin-top:15px;">
         <h5 style="text-decoration: underline; margin-left:3px; text-align:center;">Food Charges</h5>
        </div>
        <table width="100%" style="margin-top:0px;">
            <thead>
                <tr style="background-color: rgb(207, 237, 241);">
                <th style="text-align: center; color: rgb(15, 1, 1);">SL</th>
                <th style="text-align: center; color: rgb(15, 1, 1);">Item Name</th>
                <th style="text-align: center; color: rgb(15, 1, 1);">UOM</th>
                <th style="text-align: center; color: rgb(15, 1, 1);">Qty</th>
                <th style="text-align: center; color: rgb(15, 1, 1);">Rate(BDT)</th>
                <th style="text-align: center; color: rgb(15, 1, 1);">Price(BDT)</th>
                <th style="text-align: center; color: rgb(15, 1, 1);">VAT(BDT)</th>
                <th style="text-align: center; color: rgb(15, 1, 1);">Discount(BDT)</th>
                </tr>
            </thead>
            <tbody>
                {{-- @dd($result) --}}
                @foreach($result['menu'] as $key => $data)
                    <tr>
                        <td style="text-align: center;">{{$key+1}}</td>
                        <td style="text-align: left;" width="20%">{{$data['ItemName']}}</td>
                        <td style="text-align: center;">{{$data['UomCode']}}</td>
                        <td style="text-align: center;">{{$data['OrderQty']}}</td>
                        <td style="text-align: right;">{{ number_format($data['ItemRate'], 2, '.', ',') }}</td>
                        <td style="text-align: right;">{{ number_format($data['Amount'], 2, '.', ',') }}</td>
                        <td style="text-align: right;">{{ number_format($data['VatAmount'], 2, '.', ',') }}</td>
                        <td style="text-align: right;">{{ number_format($data['Discount'], 2, '.', ',') }}</td>
                    </tr>
                @endforeach
                    <tr style="border-bottom: 1px solid black;">
                        <td style="text-align: right;"colspan ="7"><strong> Total Amount(with VAT) : </strong></td>
                        <td style="text-align: right;"><strong>{{ number_format(collect($result['menu'])->sum('TotalAmountWithVat'), 2, '.', ',') }}</strong></td>
                    </tr>
                    <tr style="border-bottom: 1px solid black;">
                        <td style="text-align: right;"colspan ="7"><strong> Total Discount : </strong></td>
                        <td style="text-align: right;"><strong>{{ number_format(collect($result['menu'])->sum('Discount'), 2, '.', ',') }}</strong></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;"colspan ="7"><strong> Total : </strong></td>
                        <td style="text-align: right;"><strong>{{ number_format(collect($result['menu'])->sum('TotalAmount'), 2, '.', ',') }}</strong></td>
                </tr>
            </tbody>
        </table>
        <div style="margin-left:3px; margin-top:5px;">
            <h5 style="text-decoration: underline; margin-left:3px; text-align:center;">Ride Charges</h5>
        </div>
           <table width="100%" style="margin-top:0px;">
            <thead>
                <tr style="background-color: rgb(207, 237, 241);">
                    <th style="text-align: center; color: rgb(15, 1, 1);">SL</th>
                    <th style="text-align: center; color: rgb(15, 1, 1);">Item Name</th>
                    <th style="text-align: center; color: rgb(15, 1, 1);">UOM</th>
                    <th style="text-align: center; color: rgb(15, 1, 1);">Qty</th>
                    <th style="text-align: center; color: rgb(15, 1, 1);">Rate(BDT)</th>
                    <th style="text-align: center; color: rgb(15, 1, 1);">Price(BDT)</th>
                    <th style="text-align: center; color: rgb(15, 1, 1);">VAT(BDT)</th>
                    <th style="text-align: center; color: rgb(15, 1, 1);">Discount</th>
                </tr>
            </thead>
            <tbody>
                {{-- @dd($result) --}}
                @foreach($result['ride'] as $key => $data)
                <tr>
                    <td style="text-align: center;">{{$key+1}}</td>
                    <td style="text-align: left;" width="20%">{{$data['ItemName']}}</td>
                    <td style="text-align: center;">{{$data['UomCode']}}</td>
                    <td style="text-align: center;">{{$data['OrderQty']}}</td>
                    <td style="text-align: right;">{{ number_format($data['ItemRate'], 2, '.', ',') }}</td>
                    <td style="text-align: right;">{{ number_format($data['Amount'], 2, '.', ',') }}</td>
                    <td style="text-align: right;">{{ number_format($data['VatAmount'], 2, '.', ',') }}</td>
                    <td style="text-align: right;">{{ number_format($data['Discount'], 2, '.', ',') }}</td>
                </tr>
                @endforeach
                <tr style="border-bottom: 1px solid black;">
                    <td style="text-align: right;"colspan ="7"><strong> Total Amount(with VAT) : </strong></td>
                    <td style="text-align: right;"><strong>{{ number_format(collect($result['ride'])->sum('TotalAmountWithVat'), 2, '.', ',') }}</strong></td>
                </tr>
                <tr style="border-bottom: 1px solid black;">
                    <td style="text-align: right;"colspan ="7"><strong>Total Discount : </strong></td>
                    <td style="text-align: right;"><strong>{{ number_format(collect($result['ride'])->sum('Discount'), 2, '.', ',') }}</strong></td>
                </tr>
                <tr>
                    <td style="text-align: right;"colspan ="7"><strong> Total: </strong></td>
                    <td style="text-align: right;"><strong>{{ number_format(collect($result['ride'])->sum('TotalAmount'), 2, '.', ',') }}</strong></td>
            </tr>
            </tbody>
        </table>
        <div style="margin-left:3px; margin-top:5px;">
            <h5 style="text-decoration: underline; margin-left:3px; text-align:center;">Hall room & Other Charges</h5>
        </div>
           <table width="100%" style="margin-top:0px;">
            <thead>
                <tr style="background-color: rgb(207, 237, 241);">
                    <th style="text-align: center; color: rgb(15, 1, 1);">SL</th>
                    <th style="text-align: center; color: rgb(15, 1, 1);">Particulars</th>
                    <th style="text-align: center; color: rgb(15, 1, 1);">UOM</th>
                    <th style="text-align: center; color: rgb(15, 1, 1);">Qty</th>
                    <th style="text-align: center; color: rgb(15, 1, 1);">Rate(BDT)</th>
                    <th style="text-align: center; color: rgb(15, 1, 1);">Price(BDT)</th>
                    <th style="text-align: center; color: rgb(15, 1, 1);">VAT(BDT)</th>
                    <th style="text-align: center; color: rgb(15, 1, 1);">Discount(BDT)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($result['service'] as $key => $data)
                    <tr>
                        <td style="text-align: center;">{{$key+1}}</td>
                        <td style="text-align: left;" width="20%">{{$data['ItemName']}}</td>
                        <td style="text-align: center;">{{$data['UomCode']}}</td>
                        <td style="text-align: center;">{{$data['OrderQty']}}</td>
                        <td style="text-align: right;">{{ number_format($data['ItemRate'], 2, '.', ',') }}</td>
                        <td style="text-align: right;">{{ number_format($data['Amount'], 2, '.', ',') }}</td>
                        <td style="text-align: right;">{{ number_format($data['VatAmount'], 2, '.', ',') }}</td>
                        <td style="text-align: right;">{{ number_format($data['Discount'], 2, '.', ',') }}</td>
                    </tr>
                @endforeach

                <tr style="border-bottom: 1px solid black;">
                    <td style="text-align: right;"colspan ="7"><strong> Total Amount(with VAT) : </strong></td>
                    <td style="text-align: right;"><strong>{{ number_format(collect($result['service'])->sum('TotalAmountWithVat'), 2, '.', ',') }}</strong></td>
                </tr>
                <tr style="border-bottom: 1px solid black;">
                    <td style="text-align: right;"colspan ="7"><strong> Total Discount: </strong></td>
                    <td style="text-align: right;"><strong>{{ number_format(collect($result['service'])->sum('Discount'), 2, '.', ',') }}</strong></td>
                </tr>
                <tr>
                    <td style="text-align: right;"colspan ="7"><strong> Total: </strong></td>
                        <td style="text-align: right;"><strong>{{ number_format(collect($result['service'])->sum('TotalAmount'), 2, '.', ',') }}</strong></td>
                </tr>


                <tr>
                    <td style="text-align: right; "colspan ="7"><strong> Sub Total Discount : </strong></td>
                    <td style="text-align: right;"><strong>
                    @php
                    $total_sum = 0;
                    if (isset($result['menu'])) {
                        foreach ($result['menu'] as $menu) {
                            $total_sum += floatval($menu['Discount']);
                        }
                    }
                    if (isset($result['ride'])) {
                        foreach ($result['ride'] as $ride) {
                            $total_sum += floatval($ride['Discount']);
                        }
                    }
                    if (isset($result['service'])) {
                        foreach ($result['service'] as $service) {
                            $total_sum += floatval($service['Discount']);
                        }
                    }

                    echo number_format($total_sum, 2) . "<br>";
                    @endphp
                    </td>
                </tr>

                <tr style="border-bottom: 1px solid black;">
                    <td style="text-align: center; "colspan ="7"><strong> GRAND TOTAL :</td>

                   <td style="text-align: right;"><strong>
                    @php
                        $total_sum = 0;

                        // Count "TotalAmount" in the "menu" index
                        if (isset($result['menu'])) {
                            foreach ($result['menu'] as $menu) {
                                $total_sum += floatval($menu['TotalAmount']);
                            }
                        }

                        // Count "TotalAmount" in the "ride" index
                        if (isset($result['ride'])) {
                            foreach ($result['ride'] as $ride) {
                                $total_sum += floatval($ride['TotalAmount']);
                            }
                        }

                        // Count "TotalAmount" in the "service" index
                        if (isset($result['service'])) {
                            foreach ($result['service'] as $service) {
                                $total_sum += floatval($service['TotalAmount']);
                            }
                        }

                        echo number_format($total_sum, 2) . "<br>";
                        @endphp

                        </strong>
                    </td>
                    </tr>

            </tbody>
        </table>
        <div class="footer">
            <p style="font-size:10px;">
                *Terms and Condition
                <br>
                <ol style="font-size:11px;">
                    <li>Program/Party can not be changed after booking.</li>
                    <li>You can not reduce the guest quantity after booking a program or party.</li>
                    <li>Chiklee authorities can change/cancel the party with acknowledgement.</li>
                    <li>Without card (contain QR code) validation no huests will not enter into the park.</li>
                </0l>
            </p>
            <p style="font-size:10px; margin-left:150px;">**This commercial invoice is elctronically generated! Signature is not required.</p>
        </div>
        <htmlpagefooter name="page-footer">
            <div class="rowfooter" style="none">
                <div class="columnfooter">
                    <img class="chiklee" src="http://chiklee-park.com/assets/img/2022-01-04/A2.png" alt="">
                    <p style="font-size: 10px; margin-left:20px; margin-top:-2px;">A Product by Z IT</p>
                </div>
                <div class="columncall">
                    <img class="footimg" src="https://cdn-icons-png.flaticon.com/512/8374/8374177.png" alt="">
                    <p style="font-size:10px; margin-left:30px; margin-top:-15px;">01762 620404</p>
                </div>
                <div class="columnemail">
                <img class="footimg" src="https://cdn-icons-png.flaticon.com/512/1000/1000885.png" alt="">
                    <p style="font-size:10px; margin-left:30px; margin-top:-15px;">service@nature-account.com</p>
                </div>
                <div class="columnworld">
                    <img class="footimg" src="https://cdn-icons-png.flaticon.com/512/3870/3870941.png" alt="">
                    <p style="font-size:10px; margin-left:30px; margin-top:-15px;">www.chiklee-park.com</p>
                </div>
                <div class="column1">
                    <img class="footimg" src="https://cdn-icons-png.flaticon.com/512/727/727606.png" alt="">
                    <p style="font-size:10px; margin-left:30px; margin-top:-25px;">Chiriyakhana Road,<br>
                    Honuman Tola, Rangpur.</p>
                </div>
            </div>
        </htmlpagefooter>
    </html>