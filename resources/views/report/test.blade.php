<!DOCTYPE html>
<html>
<head>
    <title>Books BD</title>
</head>
<style type="text/css">
    @page {
            header: page-header;
            footer: page-footer;
        }
    body{
        font-family: 'Times New Roman', Times, serif;
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
        font-size:8px;
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
    .column2{
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
    .columnl{
        float: right;
        width: 8%;
    }
    .column2 {
        float: left;
        width: 66.66%;
    }
    .column3{
        display: flex;
        float: left;
        width: 30%;
        padding-top: 10px;
        margin-left: 40px;
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
    }
    .columnzit{
        float: left;
        width: 30%;
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
        width: 55%;
        height: 2rem;
        padding-top: -10px;
    }
    .mr{
        margin-left: 80px;
        text-align: center;
        width: 50%;
        background-color: #188764;
        border-radius: 2px;
        font-size: x-large;
    }
    .mr2{
        font-size: 12px;
    }
    .sig{
        display: flex;
        float: left;
        width: 20%;
        text-align: left;
    }
    .mFooter{
        font-size: 12px;
    }
    .mfooter1{
        display: flex;
        float: left;
        width: 50%;
        text-align: center;
    }
    .last{
        margin-left: -40px;
    }
    .last1{
        margin-left: -200px;
    }
    .bg{
        background-color: rgb(3,73,91);
    }
</style>
<body class="bg">
    <div class="row" style="none">
        <div class="column1">
            <!-- <img class="logo" src="https://www.pinclipart.com/picdir/middle/187-1872894_bangladesh-govt-logo-png-clipart.png" alt=""> -->
            <p class="mr"><strong>Money Receipt</strong></p>
            <p class="mr2">
                Receipt No: <span>000015</span><br>
                Received with thanks from: <b>Syed Mostafa Jamal</b><br>
                <b>Amount in word: Thirty Thousands Five Hundred BDT only</b>
                <div class="footer" style="font-size: x-small;">
                <b>Received By:</b> <br>
                    <div class="sig">
                        <input type="checkbox" id="Cash" name="Cash">
                        <label for="vehicle1">Cash</label>
                    </div>
                    <div class="sig">
                        <input type="checkbox" id="Cash" name="Cash">
                        <label for="vehicle1">Bank</label>
                    </div>
                    <div class="sig">
                        <input type="checkbox" id="Cash" name="Cash">
                        <label for="vehicle1">MFS</label>
                    </div>
                    <b>Reference: 336748994</b>
                </div>
                <div style="margin-top:10px; font-size: x-small;">
                    <label for="amount">Amount:</label>
                    <input style="width:150px;" type="text" id="amount">
                </div>
            </p>
        </div>
        <div class="column3">
            <div class="head_middle">
                <div  style="text-align:left; padding-top:-15px; font-size: medium;">
                    <p >
                        <b style="font-size: large;">Chiklee Water Park</b> <br>
                        <span style="font-size: xx-small;">Chiriakhana road, Islampur,<br> Honuman tola 5400 Rangpur,
                            <br>Rangpur Division, Babgladesh<br><b>phone: 01762-620404</b><br><b>Email: service@nature-aquatics.com</b>
                            <br><b>Date:</b> 
                                @php 
                                    $mytime = Carbon\Carbon::now()->format ('d-m-Y h:i A');
                                    echo $mytime;
                                @endphp
                        </span> 
                    </p>
                </div>
            </div>
        </div>
        <div class="columnl" style="padding-top:20px; margin-left:10px;">
            <img width="40px" height="40px" src="https://cdn-icons-png.flaticon.com/512/714/714390.png" alt="">
        </div>
    </div>
    <div class="mFooter" style="margin-top:30px; font-size: x-small;">
        <div class="mFooter1">
            <p class="last1">______________________________ <br> Received By</p>
        </div>
        <div class="mFooter1">
            <p class="last">________________________________ <br> Authorized Signature</p>
        </div>
    </div>
</html>