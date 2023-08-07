<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css" integrity="sha512-Ez0cGzNzHR1tYAv56860NLspgUGuQw16GiOOp/I2LuTmpSK9xDXlgJz3XN4cnpXWDmkNBKXR/VDMTCnAaEooxA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        footer{
            page-break-after: always;
        }
        .p_cls{
            margin: 0px;
            padding: 0px;
            font-size: 9px;
            text-align: center !important;
        }
        @media print {
            footer{
                page-break-after: always;
            }
            p{
                text-align: center !important;
            }
        }
        span{
            font-size: 8px;
        }
        .main_p{
            text-align:center;
            display: inline-block;
            margin: 5px 30px 0px 30px;
        }
    </style>
</head>
<body>    
    <main class="row">
        @php 
            $i=1; 
            $a=1;
            $count=8;         
        @endphp
        @foreach($result['card_item'] as $cardItem)
            @if($a > 15)
                @php 
                    $count--;
                    $a=0;                     
                @endphp
            @endif
        @php $a++; @endphp
        <div class="col-4" style="margin-top: 0px;">
            <!-- {{-- <h6 class="p_cls">{{$result['customer_name']}}</h6>
            <h6 class="p_cls">{{date('d-M-Y', strtotime($result['prog_date']))}}</h6> --}} -->
            <p  class="main_p">
                @if(!empty($cardItem['card_id']))
                    {{-- {{QrCode::size(60)->generate('{id:'.$result['id'].',prog:'.$result['program_name'].',date :"'.$result['prog_date'].'",code :'.$cardItem['card_id'].'}')}} --}}
                    {{ QrCode::size(60)->generate($cardItem['card_id']) }}
                    {{-- {{QrCode::size(70)->generate($cardItem['card_id'])}} --}}
                    {{-- {{ $cardItem['card_id'] }} --}}

                @endif
            </p>
            {{-- <h6 class="p_cls">{{$cardItem['card_id']}}</h6> --}}
        </div>
            @if($i == 3) 
            <footer style="margin-bottom: 10px;"></footer> 
            @php $i=0 @endphp
            @endif
        @php $i++ @endphp
        @endforeach
    </main>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
    window.print();
    window.onafterprint = window.close;
</script>
</body>
</html>