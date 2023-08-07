<html lang="en">

<head>
    <title>Ticket Receipt</title>
    <style>
        body {
            width: 120px;
        }

        .border {
            border-bottom: 2px dotted black;

        }

        .padding {
            padding: 5px;
        }

        .align {
            text-align: right;
        }

        @media print {
            footer {
                page-break-after: always;
            }
        }
    </style>
    <link href='https://fonts.googleapis.com/css?family=Libre Barcode 39' rel='stylesheet'>

</head>

<body id="main" onclick="window.close()">
    @foreach($data as $item)
    @for($j=0; $j<$item->product_quantity;$j++)
        <div style="width: 350px;  text-align: center; padding: 10px;">
            <div>
                <img src="{{asset('image/chiklee.jpg')}}" alt="Chiklee" style="width: 120px;">
            </div>
            <div style="float: left; width: 100%; border-top: 2px dotted black;">
                <h1 style="margin: 0;">Ticket</h1>
            </div>
            <div style="float:left; width: 100%" class="border">
                <p style="text-align: left; margin: 0;">{{$issue_master_id}}/A{{$j}}
                    <span style="float: right;">{{date('d-m-Y')}} {{date('h:i A')}}</span>
                </p>
            </div>
            <table width="350px" cellspacing="0">
                <tr>
                    <td class="border padding"><b>SL</b></td>
                    <td class="border padding"><b>Item Description</b></td>
                    <td class="border padding align"><b>Price</b></td>
                </tr>
                <tr>
                    <td class="padding border">{{$loop->iteration}}.</td>
                    <td class="padding border" style="font-size: 20px;">{{$item->product_name}}</td>
                    <td class="padding border align">৳ {{number_format($item->product_price,2)}}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;" colspan="2" class="padding">Sub-Total: </td>
                    <td style="font-weight: bold;" class="padding align">৳ {{number_format($item->product_price,2)}}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="padding border">Discount: </td>
                    <td class="padding border align">৳ 0.00</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; font-size: 22px" colspan="2" class="padding border">Total <span
                            style="font-size: 12px; font-weight: normal;">(Including VAT)</span>: </td>
                    <td style="font-weight: bold;" class="padding align border">৳
                        {{number_format($item->product_price,2)}}</td>
                </tr>
            </table>
            @if($item->product_id == 'ITI-22-000089')
            <p style="font-weight: bold; margin-bottom: 5px;">সময়: ১ ডিসেম্বর ২০২২ (বৃহস্পতিবার) রাত ০৮:০০ থেকে ২
                ডিসেম্বর ২০২২ (শুক্রবার) সন্ধ্যা ৬:০০ টা</p>
            <p style="font-weight: bold; margin-bottom: 5px;">১. এক ঘাট পাঁচ ছিপ টিকিট মূল্য ৫০০০ টাকা।</p>
            <p style="font-weight: bold; margin-bottom: 5px;text-align:left;">২. এনকোর খ্যাচ মারা যাবে না। </p>
            <p style="font-weight: bold; margin-bottom: 5px;">৩. টিকিট ক্রয় মাত্র আপনি আপনার পছন্দের ঘাট নম্বর পেতে
                পারেন। </p>
            <p style="font-weight: bold; margin-bottom: 5px;">৪. কোন প্রকার খাবার এবং পুরস্কারের ব্যবস্থা নাই। </p>
            <p style="font-weight: bold; margin-bottom: 5px;text-align:center;">------------------</p>
            <p style="font-weight: bold; margin-bottom: 5px;">কর্তৃপক্ষ চিকলি ওয়াটার পার্ক রংপুর।</p>
            <p style="font-weight: bold; margin-bottom: 5px;">যোগাযোগ করুন: 01722360530, 01739683284।</p>
            {{--//// item id 88 --}}
            {{-- <p style="font-weight: bold; margin-bottom: 5px;">সময় : ৩ নভেম্বর ২০২২ রাত ১০:০০ থেকে ৪ নভেম্বর ২০২২
                সন্ধ্যা ৬:০০ টা</p>
            <p style="font-weight: bold; margin-bottom: 5px;">১. এক টিকিটে সর্বোচ্চ ৫ টি ছিপ ফেলা যাবে।</p>
            <p style="font-weight: bold; margin-bottom: 5px;">২. লটারির মাধ্যমে স্থান নির্বাচন করা হবে। </p>
            <p style="font-weight: bold; margin-bottom: 5px;">৩. কোন রকম এংকর/খ্যাস টানা যাবে না। </p>
            <p style="font-weight: bold; margin-bottom: 5px;">৪. যেকোন নিয়মনীতি পরিবর্তন এর সম্পুর্ণ ক্ষমতা চিকলী এগ্রো
                লিমিটেড কর্তৃপক্ষ বহন করে। </p> --}}
            @endif
            @if (date('H') >= 18 && $item->product_id == 'ITI-22-000016')
            <p style="font-weight: bold; margin-bottom: 5px;">Get 30Tk Refund Over 300Tk Purchase in Restaurant.</p>
            @endif
            @if($udata['userName'] != null)
            <p style="font-weight: bold; margin-bottom: 5px;">Name : {{$udata['userName']}}</p>
            <p style="font-weight: bold; margin-bottom: 5px;">Phone : {{$udata['userMobile']}}</p>
            @endif
            <p style="font-weight: bold; margin-bottom: 5px;">THANK YOU</p>
            <p style="font-family: 'Libre Barcode 39';font-size: 50px; margin: 0;" class="border">{{$issue_master_id}}
            </p>
            <div style="float: left; width: 100%">
                <p style="text-align: center; font-size: 12px;">
                    Powered by - <b>Z IT Solutions Ltd.</b>
                </p>
            </div>

        </div>
        <br><br><br>
        <footer></footer>
        @endfor
        @endforeach
        <script src="https://code.jquery.com/jquery-3.6.3.min.js"
            integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
        <script>
            window.onclick = function() {
                window.close()
            }
            window.onmousemove = function() {
                setTimeout(displayDate, 100);

            }
            document.getElementById("main").addEventListener("click", displayDate);

            function displayDate() {

                window.close()
            }
            document.addEventListener('contextmenu', event => event.preventDefault());
            $(document).on('keydown', function(e) {
                if((e.ctrlKey || e.metaKey) && (e.key == "p" || e.charCode == 16 || e.charCode == 112 || e.keyCode == 80) ){
                    //alert("Please use the Print PDF button below for a better rendering on the document");
                    e.cancelBubble = true;
                    e.preventDefault();

                    e.stopImmediatePropagation();
                }
            });
            window.print();
            window.onfocus=function(){ window.close();}

            /**
            window.onafterprint = (event) => {
                alert('After print1');
            };
            window.onafterprint = function(e){
                alert('After print2');
            };
            window.addEventListener('afterprint', (event) => {
                alert('After print4');
            });

            //function to call if you want to print
            var onPrintFinished=function(printed){
                window.print();
            }**/

       
        </script>



</body>

</html>