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

        font-family: "Nikosh";
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
        .footer{
        margin-top: 450px;
        }
        .sig{
            display: flex;
            float: left;
            width: 33%;
            text-align: center;
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
                    <div  style="text-align: center; padding-top:-15px;">
                        <p style="font-size: 18pt;">
                            <strong>Chiklee Water Park</strong>
                        </p>
                    </div>
                    <div style="text-align: center; padding-top:-40px;">
                        <p>Issue Return for Program<br>
                        </p>
                    </div>
                </div>
            </div>
            <div class="column1">
                <p style="text-align: right; font-size:12px;">Chiklee#C_A-01</p>
            </div>
        </div>
        <div style="margin-left:3px; margin-top:20px;">
            <p style="text-align: left; font-size: 12px">
            Returning Store: <br><br><br><br>
            Receive Store: <br>
            </p>
            <div class="columne" style="text-align:right; float:right; margin-right:10px; font-size: 12px;">
          
                <p style="padding-top:-15px;"><strong>Print: </strong>
                    @php 
                        $mytime = Carbon\Carbon::now()->format ('d-m-Y h:i A');
                        echo $mytime;
                    @endphp
                    <br>
                    Issue No: <br>
                    Issue Date:<br>
                    Program Name: <br>
                    Program Date:
                </p>
            </div>
        </div>
            <table width="100%" style="margin-top:50px;">
                <thead>
                    <tr style="background-color: rgb(3,73,91);">
                        <th style="text-align: center; color: rgb(255,255,255);">SL</th>
                        <th style="text-align: center; color: rgb(255,255,255);">Item Name</th>
                        <th style="text-align: center; color: rgb(255,255,255);">UOM</th>
                        <th style="text-align: center; color: rgb(255,255,255);">Indent Qty</th>
                        <th style="text-align: center; color: rgb(255,255,255);">Issue Qty</th>
                        <th style="text-align: center; color: rgb(255,255,255);">Return Qty</th>
                        <th style="text-align: center; color: rgb(255,255,255);">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;">01</td>
                        <td style="text-align: left;">Rice</td>
                        <td style="text-align: center;">66</td>
                        <td style="text-align: center;">34</td>
                        <td style="text-align: center;">86</td>
                        <td style="text-align: center;">34</td>
                        <td style="text-align: left;">Weeding Ceremony</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">02</td>
                        <td style="text-align: left;">Mutton</td>
                        <td style="text-align: center;">32</td>
                        <td style="text-align: center;">45</td>
                        <td style="text-align: center;">54</td>
                        <td style="text-align: center;">90</td>
                        <td style="text-align: left;">For the program.</td>
                    </tr>
                    <tr style="border-bottom: 1px solid black;">
                        <td style="text-align: right;"colspan ="3"><strong>Sub Total: </strong></td>
                        <td style="text-align: center;"><strong>{{number_format(3000, 2, '.', ',')}}</strong></td>
                        <td style="text-align: center;"><strong>{{number_format(3654, 2, '.', ',')}}</strong></td>
                        <td style="text-align: center;"><strong>{{number_format(3654, 2, '.', ',')}}</strong></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <P>**Show full Indent Qty, then Issued qty and then return qty</p>
            <div class="footer" style="margin-left: 20px;">
            <div class="sig">
                <p>_______________________________<br> Submitted By</p>
            </div>
            <div class="sig">
                <p>_______________________________<br> Recommended By</p>
            </div>
            <div class="sig">
                <p>_______________________________<br> Approved By</p>
            </div>
        </div>
        <htmlpagefooter name="page-footer">
            <div class="row" style="none">
                <div class="columnzit">
                    <!-- <img class="logo" src="https://www.pinclipart.com/picdir/middle/187-1872894_bangladesh-govt-logo-png-clipart.png" alt=""> -->
                    <p><span style="font-size: 18px; text-align:center;"><strong>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Chiklee </strong></span> 
                        <br>
                        A product By<span style="font-size:18px;"><strong> ZIT</strong></span> 
                    </p>
                </div>
                <div class="columnmid">
                    <div class="head_middle">
                        <div  style="text-align: center;">
                        <p style="text-align: center;">{PAGENO} of {nbpg} pages </p>
                        </div>
                    </div>
                </div>
                <div class="column1">
                </div>
            </div>
        </htmlpagefooter>
    </html>