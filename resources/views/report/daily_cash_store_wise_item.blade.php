<!DOCTYPE html>

<html>

<head>

    <title>Daily Cash Receive</title>

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
                <img class="logo" src="http://chiklee-park.com/assets/img/2022-01-04/A2.png" alt="">
            </div>
            <div class="column3">
                <div class="head_middle">
                    <div  style="text-align: center; padding-top:-15px;">
                        <p style="font-size: 18pt;">
                            <strong>Chiklee Water Park</strong>
                        </p>
                    </div>
                    <div style="text-align: center; padding-top:-45px; font-size: 12pt;">
                        <p>Daily Cash Receive<br>
                        <span>Storewise Item Wise Sell</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="column1">
                <p style="text-align: right; font-size:12px;">Chiklee#D_01E</p>
            </div>
        </div>
            <div style="margin-left:5px; margin-top:20px; display:block;">

                <div class="report_params_areas" style="float:left;height:20px;width:50%">
                    <span style="text-align: right; font-size:12px"><b> Date: </b>{{ Date('d-m-Y')}}</span>
                </div>
                <div class="report_params_areas" style="float:right;height:20px;width:45%;text-align:right;margin-right:5px;">
                    <span style="text-align: right; font-size:12px"><strong>Print: </strong>{{ Date('d-m-Y h:i:s A')}}</span>
                </div>
            </div>
        <div style="display:block; margin-top:10px;">
            <table width="100%">
                <thead>
                    <tr style="background-color: rgb(3,73,91);">
                        <th style="text-align: center; color: rgb(255,255,255);">SL</th>
                        <th></th>
                        <th style="text-align: center; color: rgb(255,255,255);" width = "20%">Item Name</th>
                        <th style="text-align: center; color: rgb(255,255,255);">Qty</th>
                        <th style="text-align: center; color: rgb(255,255,255);">Price(BDT)</th>
                        <th style="text-align: center; color: rgb(255,255,255);">Sales Amount(BDT)</th>
                        <th style="text-align: center; color: rgb(255,255,255);">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;"colspan ="3"><strong>Store Name: Happy Zone </strong></td>
                        <td></td>
                        <td></td>
                        <td style="text-align: right;"><strong>1600.00</strong></td>
                        <td></td>
                    </tr>
                    <tr style="border-bottom: 1px solid black;">
                        <td style="text-align: center;">1</td>
                        <td></td>
                        <td style="text-align: left;">Singara</td>
                        <td style="text-align: center;">32</td>
                        <td style="text-align: right;">50.00</td>
                        <td style="text-align: right;">1600.00</td>
                        <td style="text-align: left;"></td>
                    </tr>
                    <tr style="border-bottom: 1px solid black;">
                        <td style="text-align: center;">2</td>
                        <td></td>
                        <td style="text-align: left;">Fuchka</td>
                        <td style="text-align: center;"></td>
                        <td style="text-align: right;"></td>
                        <td style="text-align: right;">0</td>
                        <td style="text-align: left;"></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;"colspan ="3"><strong>Store Name: Tulip </strong></td>
                        <td></td>
                        <td></td>
                        <td style="text-align: right;"><strong>4550.00</strong></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">3</td>
                        <td></td>
                        <td style="text-align: left;">Ice Cream</td>
                        <td style="text-align: center;">35</td>
                        <td style="text-align: right;">30.00</td>
                        <td style="text-align: right;">1050.00</td>
                        <td style="text-align: left;"></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">4</td>
                        <td></td>
                        <td style="text-align: left;">Cone Icecream</td>
                        <td style="text-align: center;">70</td>
                        <td style="text-align: right;">50.00</td>
                        <td style="text-align: right;">3500.00</td>
                        <td style="text-align: left;"></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;"colspan ="5"><strong>Grand Total</strong></td>
                        <td style="text-align: right;"><strong>6150.00</strong></td>
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