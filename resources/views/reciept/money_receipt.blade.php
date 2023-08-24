<!DOCTYPE html>
<html>
<head>
    <title>Chiklee</title>
</head>
<style type="text/css">
    @page {
            header: page-header;
            footer: page-footer;
    }

    body{
        font-family: Tahoma, "Trebuchet MS", sans-serif;
        background-color: rgb(3,73,91);
        /* height: auto; */
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
        font-size:15px;
    }
    table tr td{
        font-size:13px;
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
        font-size:14px;
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
    }

    .column1{
        float: left;
        width: 49.80%;
        height: 2rem;
        padding-top: -10px;
    }
    .columnsame{
        float: left;
        width: 50%;
        height: 2rem;
        padding-top: 5px;
    }

    .column3{
        float: left;
        width: 42%;
        padding-top: 10px;
        margin-left:70px;
    }
    .column{
        float: right;
        width: 5%;
        margin-left:40px;
    }
    .mr{

        background-color: rgb(20,135,120);
        border-radius: 2px;
        font-size: 16px;
        text-transform:uppercase;
        color:white;
        text-align:center;
        width:50%;
        position:center;
        padding: 10px 5px 10px 5px;
        margin-left:110px;
    }
    .mr2{
        font-size: 16px;
        margin-left:10px;
    }
    .sig{
        display: flex;
        float: left;
        width: 50%;
        text-align: left;
        font-size:18px;
    }
    .mFooter{
        float: left;
        text-align: center;
        margin-top:30px;
    }
    .mFooter1{
        float: left;
        text-align: center;
        margin-top:-100px;
        margin-left:1600px;
    }
    /* .mfooter1{
        float: left;
        width: 50%;
        text-align: center;
    } */
    .last{
        margin-top:150px;
        margin-left: -350px;
    }
    .last1{
        margin-top:150px;
        margin-left: -1700px;
    }
    .bg{

            background: url('{{ asset('logo/logo.png') }}');
            /* background: url('/bg.jpg'); */
            background-size: 60% 55%;
            background-repeat: no-repeat;
            background-position: center center;
            background-image-opacity: 0.2;
            border:1px solid black;
            /* background-color: rgb(229, 244, 244); */

        }
    .head-chiklee{
        text-align:left;
        padding-top:-15px;
        font-size:14px;
    }
    .head-chiklee p{
       font-size: 14px;
    }

    .column-chiklee
        {
            float: left;
            width: 5%;
            margin-top:-50px;
            text-align:right;
        }
    .column3
        {
            float: left;
            width: 75%;
            padding-top: 10px;
        }
    .logo
        {
            width: 75px;
            /*margin-left: 365px;*/
            margin-top: 28px;
            text-align:right;
            height:45px;
            margin-left:30px;
        }
        .logo1
        {
            width: 75px;
            margin-top: 28px;
            text-align:left;
            height:45px;
            margin-left:30px;
        }
        .footerbg {
            /* background: linear-gradient(180deg, rgb(239, 105, 185), rgb(60, 158, 215)); */
            background-color: rgb(239, 105, 185);
            padding: 20px;
            width: 100%;
            border-radius: 170px 0px 0px 0px;
            }
        .footerbg1{
            /* background: linear-gradient(180deg, rgb(180, 90, 161), rgb(60, 158, 215)); */
            background-color: rgb(95, 206, 234);
            padding: 20px;
            width:100%;
            border-radius: 0px 170px 0px 0px;
            }


    .container {
            max-width: 100%; /* Adjust the maximum width of the container as needed */
            margin: 0 auto; /* Center the container horizontally */
            }

</style>
<body>
    <div class="row" style="none">
        <div class="bg">
            <div class="column1" style="border-right:2px dotted black;">
                <div class="row">
                    <div class="column-chikle">
                        <!-- <img class="logo" src="https://www.pinclipart.com/picdir/middle/187-1872894_bangladesh-govt-logo-png-clipart.png" alt=""> -->
                        <img class="logo" src="{{ asset('logo/logo.png')}}" alt="">
                    </div>
                    <div class="column3">
                        <div class="head_middle">
                            <div  style="text-align: center; padding-top:-75px; margin-left:20px;">
                                <p style="font-size: 18pt;">
                                    <strong>Chiklee Water Park</strong>
                                </p>
                            </div>
                            <div style="text-align: center; padding-top:-40px; margin-left:10px;">
                                <p style="font-size: 8pt; margin-left:30px;">Chiriyakhana Road, Islampur. Honuman Tola, Rangpur, Bangladesh.<br>
                                    Phone: 01762-620404, Email: service@nature-aquatics.com
                                </p>
                                <p class="mr"><strong>Money ReceiptWater</strong></p>
                            </div>
                        </div>
                    </div>
                    <div class="column-chiklee">
                        <p style="text-align: right; font-size:12px; margin-top:5px; margin-right:3px;">OFFICE COPY</p>
                    </div>
                </div>
                <div style="margin-left:10px; margin-top:20px; display:block;">
                    <div class="report_params_areas" style="float:left;height:20px;width:50%">
                        <span style="text-align: right; font-size:16px;">Receipt No: {{$money[0]->Payment_id ?? ''}}</span>
                    </div>
                    <div class="report_params_areas" style="float:right;height:20px;width:45%;text-align:right;margin-right:5px;">
                        <span style="text-align: right; font-size:16px">
                            Date: <?php echo date('d-m-Y', strtotime($money[0]->payment_date ?? '')); ?>
                        </span>
                    </div>
                </div>
                <p style="margin-left:10px;">Received with thanks from:<b> {{$money[0]->customer_name?? ''}}</b> </p>
                <div class="footer" style="font-size: 16px; margin-left:10px;">
                    <button>Receive By</button>
                    <input type="button" class="button" value="{{$money[0]->paymode_name}}">
                    Reference: {{$money[0]->pay_ref ?? ''}}
                </div>
                <div style="margin-top:18px; font-size: 16px; margin-left:10px;">
                    <label for="amount">Amount(BDT)</label>
                    <input type="text" id="amount" value="{{$money[0]->paid_amount?? ''}}"><br><br>
                    Amount in word: <b>
                        {{ number_to_words_bdt(collect($money)->sum('paid_amount')) }} taka only
                    </b>
                </div>
                <div class="footer" style="margin-left: 20px;">
                    <div class="sig">
                        <p>_______________________<br> Received By</p>
                    </div>
                    <div class="sig">
                        <p>________________________<br> Authorized Signature</p>
                    </div>
                </div>
                <footer class="footerbg1">
                    <div class="container">
                    </div>
                </footer>
            </div>

            {{-- <span style="font-size:12px; padding-top:30px;">CLIENT COPY</span> --}}
            <div class="columnsame">
                <div class="row" style="none">
                    <div class="column-chikle">

                        <img class="logo1" src="{{ asset('logo/logo.png')}}" alt="">
                        <p style="font-size:12px; padding-top:-73px; margin-bottom:20px; margin-left:4px;">CLIENT COPY</p>
                    </div>
                    <div class="column3">
                        <div class="head_middle">
                            <div  style="text-align: center; padding-top:-66px; margin-left:20px;">
                                <p style="font-size: 18pt;">
                                    <strong>Chiklee Water Park</strong>
                                </p>
                            </div>
                            <div style="text-align: center; padding-top:-40px; margin-left:10px;">
                                <p style="font-size: 8pt; margin-left:30px;">Chiriyakhana Road, Islampur. Honuman Tola, Rangpur, Bangladesh.<br>
                                    Phone: 01762-620404, Email: service@nature-aquatics.com
                                </p>
                                <p class="mr"><strong>Money Receipt</strong></p>
                            </div>
                        </div>
                    </div>
                    <div class="column-chiklee">
                        <img width="40px" height="40px" src="https://cdn-icons-png.flaticon.com/512/714/714390.png" alt="">
                    </div>
                </div>
                <div style="margin-left:10px; margin-top:20px; display:block;">
                    <div class="report_params_areas" style="float:left;height:20px;width:50%">
                        <span style="text-align: right; font-size:16px;">Receipt No: {{$money[0]->Payment_id ?? ''}}</span>
                    </div>
                    <div class="report_params_areas" style="float:right;height:20px;width:45%;text-align:right;margin-right:5px;">
                        <span style="text-align: right; font-size:16px">
                            Date: <?php echo date('d-m-Y', strtotime($money[0]->payment_date ?? '')); ?>
                        </span>
                    </div>
                </div>
                <p style="margin-left:10px;">Received with thanks from:<b> {{$money[0]->customer_name?? ''}}</b> </p>
                <div class="footer" style="font-size: 16px; margin-left:10px;">
                    <button>Receive By</button>
                    <input type="button" class="button" value="{{$money[0]->paymode_name}}">
                    Reference: {{$money[0]->pay_ref ?? ''}}
                </div>
                <div style="margin-top:18px; font-size: 16px; margin-left:10px;">
                    <label for="amount">Amount(BDT)</label>
                    <input style="padding-top: 20px;" type="text" id="amount" value="{{$money[0]->paid_amount?? ''}}"><br><br>
                    Amount in word: <b>
                    {{ number_to_words_bdt(collect($money)->sum('paid_amount')) }} taka only
                    </b>

                </div>
                <div class="footer" style="margin-left: 20px;">
                    <div class="sig">
                        <p>_______________________<br> Received By</p>
                    </div>
                    <div class="sig">
                        <p>________________________<br> Authorized Signature</p>
                    </div>
                </div>
                <footer class="footerbg">
                    <div class="container">
                    </div>
                </footer>
            </div>
        </div>
    </div>

</html>
