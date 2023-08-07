
<style>
    
        .card {
            /* Add shadows to create the "card" effect */
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            transition: 0.3s;
            text-transform:none;
        }

        /* On mouse-over, add a deeper shadow */
        .card:hover {
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
        }

        /* Add some padding inside the card container */
        .container {
            padding: 2px 16px;
        }
        .subbtn
        {
            margin-top: 25px;
        }
        .btn {
            width:107%;
            display: inline-block;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            text-align: center;
            text-decoration: none;
            vertical-align: middle;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
            background-color: transparent;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            border-radius: 0.25rem;
            transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        .btn-download {
            display: inline-block;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            text-align: center;
            text-decoration: none;
            vertical-align: middle;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
            background-color: transparent;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            border-radius: 0.25rem;
            transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>
<form method="GET"  action="{{url('A_01-pdf')}}"
   @csrf
<div class="card">
    <div class="container">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="mb-3">
                            <label>From Date:</label>
                            <input type="date" class="form-control" name="fromDate">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="mb-3">
                            <label>To Date:</label>
                            <input type="date" class="form-control" name="toDate">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="subbtn">
                        <a href="#item">
                        <input type="submit" value="Submit" class="btn btn-primary">
                    </div>
                </div>
            </div>
        </div>
</form>
   
<style>
    /* .text-bold{

    font-weight: bold;

    } */

    /* .border{

    border:1px solid black;

    } */

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
    .row:after {
            content: "";
            display: table;
            clear: both;
    }
    .pb--20 {
        padding-bottom: 20px;
    }
    .pagination {
        float:right;
        display: inline-block;
        margin-bottom:30px;
        }

    .pagination a {
        color: black;
        float: left;
        padding: 8px 16px;
        text-decoration: none;
        transition: background-color .3s;
        border: 1px solid #ddd;
        }

    .pagination a.active {
        background-color: #0d6efd;
        color: white;
        border: 1px solid #4CAF50;
        }

    .pagination a:hover:not(.active) {background-color: #ddd;}
</style>
@if(request('fromDate') && request('toDate')) 
    <h4 class="text-center" style="margin-top:30px; color: rgb(3,73,91);">
        Item Wise Daily Sell Report
    </h4>
        <p style="text-align: left; font-size: 12px; color:black; margin-left:5px;">
            <strong>From Date: </strong> {{Date('d-m-Y',strtotime(request('fromDate')))}}<br>
            <strong>To Date: </strong>{{Date('d-m-Y',strtotime(request('toDate')))}}
        </p>
    <table width="100%" style="margin-top:20px;">
        <thead>
            <tr style="background-color: rgb(3,73,91);">
                <th style="text-align: center; color: rgb(255,255,255);">SL</th>
                <th style="text-align: center; color: rgb(255,255,255);">Item Name</th>
                <th style="text-align: center; color: rgb(255,255,255);">UOM</th>
                <th style="text-align: center; color: rgb(255,255,255);">Qty</th>
                <th style="text-align: center; color: rgb(255,255,255);">Price(BDT)</th>
                <th style="text-align: center; color: rgb(255,255,255);">Amount(BDT)</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($items as $key=> $item)
            <tr>
                <td style="text-align: center;">{{$key+1}}</td>
                <td style="text-align: left;">{{$item->itemName}}</td>
                <td style="text-align: center;">{{$item->uom}}</td>
                <td style="text-align: center;">{{$item->qty}}</td>
                <td style="text-align: right;">{{number_format($item->rate, 2, '.', ',')}}</td>
                <td style="text-align: right;">{{number_format($item->amount, 2, '.', ',')}}</td>
            </tr>
        @endforeach
            <tr style="border-bottom: 1px solid black;">
                <td style="text-align: right;"colspan ="3"><strong>Sub Total: </strong></td>
                <td style="text-align: center;"><strong>{{number_format(collect($items)->sum('qty'))}}</strong></td>
                <td></td>
                <td style="text-align: right;"><strong>{{number_format(collect($items)->sum('amount'),2, '.', ',')}}</strong></td>
            </tr>
        </tbody>
    </table>
    <div class="row" id= "item" style="margin-bottom: 3px solid red;">
        <div class="col-md-5">
            <div style="margin-bottom: 15px; margin-left:5px;">
            <a href="{{url('/A_01')}}?fromDate={{request('fromDate')}}&toDate={{request('toDate')}}" target="_blank" >
            <input type="button" value="Download" class="btn-download btn-primary">
            </a>
            </div>
        </div>  
        <!-- <div class="col-md-7">
            <div class="pagination">
            <a href="#">&laquo;</a>
            <a href="#">1</a>
            <a class="active" href="#">2</a>
            <a href="#">3</a>
            <a href="#">4</a>
            <a href="#">5</a>
            <a href="#">6</a>
            <a href="#">&raquo;</a>
            </div>
        </div> -->
    </div>
@endif
</body>
</html>




