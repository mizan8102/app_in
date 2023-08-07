<style>
    .card {
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
        transition: 0.3s;
    }
    .card:hover {
    box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
    }
    .container {
        padding: 2px 16px;
    }
    .subbtn{
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
    <title>Report C_02A_01 Opening Balance Info</title>
</head>
<body>
<form method="GET"  action="{{url('report/Report_C_02A_01_opening_balance_info')}}"
@csrf
<div class="card">
    <div class="container">
        <div class="card-body">
            <div class="row">
            <div class="col-md-3">
                    <div class="mb-3">
                        <label><b>Receive Master ID</b></label>
                        <select name="recvMasterId" class="form-select">
                                 <option value="">Select</option>
                                 <option value="21">21</option>
                          </select>
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
        .text-bold{
            font-weight: bold;
        }
        body{
        font-family: 'Times New Roman', Times, serif;
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
            font-size:12px;
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

    @if(request('recvMasterId'))
    <h4 class="text-center" style="margin-top:30px;">
        Opening Balance Info
    </h4>
    <table width="100%" style="margin-top:20px;">
        <thead>
            <tr style="background-color: rgb(3,73,91);">
                <th style="text-align: center; color: rgb(255,255,255);">SL</th>
                <th style="text-align: center; color: rgb(255,255,255);">Item Name</th>
                <th style="text-align: center; color: rgb(255,255,255);">Item Name Bn</th>
                <th style="text-align: center; color: rgb(255,255,255);">OP Qty</th>
                <th style="text-align: center; color: rgb(255,255,255);">OP Price(BDT)</th>
                <th style="text-align: center; color: rgb(255,255,255);">OP Amount(BDT)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($balances as $key => $data)
                <tr>
                    <td style="text-align: center;">{{$key+1}}</td>
                    <td style="text-align: left;">{{$data->itemName}}</td>
                    <td style="text-align: left;">{{$data->itemNameBn}}</td>
                    <td style="text-align: center;">{{$data->rcvQty}}</td>
                    <td style="text-align: right;">{{number_format($data->rcvRate, 2, '.', ',')}}</td>
                    <td style="text-align: right;">{{number_format($data->rcvAmount, 2, '.', ',')}}</td>
                </tr>
            @endforeach
                <tr style="border-bottom: 1px solid black;">
                    <td style="text-align: right;"colspan ="3"><strong>Total: </strong></td>
                    <td style="text-align: center;"colspan =""><strong>{{number_format(collect($balances)->sum('rcvQty'))}}</strong></td>
                    <td></td>
                    <td style="text-align: right;"><strong>{{number_format(collect($balances)->sum('rcvAmount'),2, '.', ',')}}</strong></td>
                </tr>
        </tbody>
    </table>
    <div class="row" id= "item" style="margin-bottom: 3px solid red;">
        <div class="col-md-5">
            <div style="margin-bottom: 15px; margin-left:5px;">
                <a href="{{url('/report/C_02A_01')}}?recvMasterId={{ request('recvMasterId') }}" target="_blank">
                    <input type="button" value="PDF" class="btn-download btn-primary">
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




