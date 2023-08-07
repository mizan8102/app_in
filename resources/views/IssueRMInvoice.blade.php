<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Issue Rm Invoice</title>
</head>

<body>
  <table style="box-sizing: border-box;" width="100%" cellspacing="0" cellpadding="0">
    <tr>
      <td height="100" colspan="2" align="center">
        <img src="{{asset('image/chiklee.jpg')}}" height="60" width="100">
        <hr />
      </td>
    </tr>
    <tr style="text-align:center">
      <td height="31" colspan="2" style="
        padding-left: 10px;
        font-size: 20px;
        font-family: Verdana, Geneva, sans-serif;
      ">
        <strong>ISSUE RAW MATERIAL INVOICE</strong>
      </td>
    </tr>
    <tr>
      <td width="61%" height="28">
        <table style="box-sizing: border-box; border: 1px solid #c8c8c8; margin: 10px" width="90%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="25%" height="25" style="
              padding-left: 10px;
              font-family: Verdana, Geneva, sans-serif;
              border-bottom: 1px solid #c8c8c8;
              border-right: 1px solid #c8c8c8;
              font-size: 14px;
            ">
              <strong>Received By </strong>
            </td>
            <td width="75%" style="
              padding-left: 10px;
              font-family: Verdana, Geneva, sans-serif;
              border-bottom: 1px solid #c8c8c8;
              font-size: 14px;
            ">
              {{$orderItem['received_by']}}
            </td>
          </tr>
          <tr>
            <td height="25" style="
              padding-left: 10px;
              font-family: Verdana, Geneva, sans-serif;
              border-right: 1px solid #c8c8c8;
              font-size: 14px;
            ">
              <strong>Address</strong>
            </td>
            <td style="
              padding-left: 10px;
              font-family: Verdana, Geneva, sans-serif;
              font-size: 14px;
            ">
              Chiklee Water Park, Rangpur
            </td>
          </tr>

          <!-- <tr>
          <td
            height="25"
            style="
              padding-left: 10px;
              font-family: Verdana, Geneva, sans-serif;
              border-right: 1px solid #c8c8c8;
              border-top: 1px solid #c8c8c8;
              font-size: 14px;
            "
          >
            <strong>Mobile</strong>
          </td>
          <td
            style="
              padding-left: 10px;
              font-family: Verdana, Geneva, sans-serif;
              border-top: 1px solid #c8c8c8;
              font-size: 14px;
            "
          >
            +91-88888888888
          </td>
        </tr> -->
        </table>
      </td>
      <td width="39%" align="right">
        <table style="box-sizing: border-box; border: 1px solid #c8c8c8; margin: 10px" width="80%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="25" align="right" style="
              padding-right: 10px;
              font-family: Verdana, Geneva, sans-serif;
              border-bottom: 1px solid #c8c8c8;
              border-right: 1px solid #c8c8c8;
              font-size: 14px;
            ">
              <strong>Received ID</strong> : {{$orderItem['received_by']}}
            </td>
          </tr>
          <tr>
            <td height="25" align="right" style="
              padding-right: 10px;
              font-family: Verdana, Geneva, sans-serif;
              border-right: 1px solid #c8c8c8;
              font-size: 14px;
            ">
              <strong>Date</strong> : {{date('d-m-Y')}}
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td height="28" colspan="2"></td>
    </tr>
    <tr>
      <td style="padding: 10px" height="28" colspan="2">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="13%" height="28" align="center" style="
              border-bottom: 1px solid #c8c8c8;
              border-right: 1px solid #c8c8c8;
              border-left: #c8c8c8 1px solid;
              border-top: #c8c8c8 1px solid;
              font-family: Verdana, Geneva, sans-serif;
              font-size: 13px;
              background-color: #c8c8c8;
              color: red;
            ">
              <strong>S.N</strong>
            </td>
            <td width="22%" align="center" style="
              border-bottom: 1px solid #c8c8c8;
              border-right: 1px solid #c8c8c8;
              border-top: #c8c8c8 1px solid;
              font-family: Verdana, Geneva, sans-serif;
              font-size: 13px;
              background-color: #c8c8c8;
              color: red;
            ">
              <strong>DESCRIPTION </strong>
            </td>
            <td width="26%" align="center" style="
              border-bottom: 1px solid #c8c8c8;
              border-right: 1px solid #c8c8c8;
              border-top: #c8c8c8 1px solid;
              font-family: Verdana, Geneva, sans-serif;
              font-size: 13px;
              background-color: #c8c8c8;
              color: red;
            ">
              <strong>AMOUNT</strong>
            </td>
            <td width="20%" align="center" style="
              border-bottom: 1px solid #c8c8c8;
              border-right: 1px solid #c8c8c8;
              border-top: #c8c8c8 1px solid;
              font-family: Verdana, Geneva, sans-serif;
              font-size: 13px;
              background-color: #c8c8c8;
              color: red;
            ">
              <strong>UNIT</strong>
            </td>
            <td width="20%" align="center" style="
              border-bottom: 1px solid #c8c8c8;
              border-right: 1px solid #c8c8c8;
              border-top: #c8c8c8 1px solid;
              font-family: Verdana, Geneva, sans-serif;
              font-size: 13px;
              background-color: #c8c8c8;
              color: red;
            ">
              <strong>QUANTITY</strong>
            </td>
            <td width="19%" align="center" style="
              border-bottom: 1px solid #c8c8c8;
              border-right: 1px solid #c8c8c8;
              border-top: #c8c8c8 1px solid;
              font-family: Verdana, Geneva, sans-serif;
              font-size: 13px;
              background-color: #c8c8c8;
              color: red;
            ">
              <strong>TOTAL AMOUNT</strong>
            </td>
          </tr>
          @php $grand_total = 0 @endphp
          @foreach($orderItem['item_row'] as $row)
          <tr>
            <td style="
              border-bottom: 1px solid #c8c8c8;
              border-right: 1px solid #c8c8c8;
              border-left: #c8c8c8 1px solid;
            " height="28" align="center">
              {{$loop->iteration}}
            </td>
            <td style="
              border-bottom: 1px solid #c8c8c8;
              border-right: 1px solid #c8c8c8;
            " align="center">
              {{$row['display_itm_name_bn']}}
            </td>
            <td style="
              border-bottom: 1px solid #c8c8c8;
              border-right: 1px solid #c8c8c8;
            " align="center">
              {{$row['issue_rate']}}
            </td>
            <td style="
              border-bottom: 1px solid #c8c8c8;
              border-right: 1px solid #c8c8c8;
            " align="center">
              {{$row['uom_short_code']}}
            </td>
            <td style="
              border-bottom: 1px solid #c8c8c8;
              border-right: 1px solid #c8c8c8;
            " align="center">
              {{$row['issue_qty']}}
            </td>
            <td style="
              border-bottom: 1px solid #c8c8c8;
              border-right: 1px solid #c8c8c8;
            " align="center">
              {{$total_amount = $row['issue_rate']*$row['issue_qty']}}
            </td>
          </tr>
          @php $grand_total = $grand_total+$total_amount @endphp
          @endforeach
        </table>
      </td>
    </tr>
    <tr>
      <td style="padding: 10px" height="28"> </td>
      <td style="padding: 10px" height="28">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td style="
              border-bottom: 1px solid #c8c8c8;
              border-right: 1px solid #c8c8c8;
              border-top: 1px solid #c8c8c8;
              border-left: 1px solid #c8c8c8;
              font-family: Verdana, Geneva, sans-serif;
              font-size: 13px;
              padding-left: 10px;
            " width="51%" height="29">
              <strong>Total Amount</strong>
            </td>
            <td width="49%" align="center" style="
              border-bottom: 1px solid #c8c8c8;
              border-right: 1px solid #c8c8c8;
              border-top: 1px solid #c8c8c8;
            ">
              {{ceil($grand_total)}}
            </td>
          </tr>
          <!-- <tr>
          <td
            style="
              border-bottom: 1px solid #c8c8c8;
              border-right: 1px solid #c8c8c8;
              border-left: 1px solid #c8c8c8;
              font-family: Verdana, Geneva, sans-serif;
              font-size: 13px;
              padding-left: 10px;
            "
            height="29"
          >
            <strong>GST </strong>
          </td>
          <td
            align="center"
            style="
              border-bottom: 1px solid #c8c8c8;
              border-right: 1px solid #c8c8c8;
            "
          >
            200
          </td>
        </tr>
        <tr>
          <td
            style="
              border-bottom: 1px solid #c8c8c8;
              border-right: 1px solid #c8c8c8;
              border-left: 1px solid #c8c8c8;
              font-family: Verdana, Geneva, sans-serif;
              font-size: 13px;
              padding-left: 10px;
            "
            height="29"
          >
            <strong>Total Amount</strong>
          </td>
          <td
            align="center"
            style="
              border-bottom: 1px solid #c8c8c8;
              border-right: 1px solid #c8c8c8;
            "
          >
            6200
          </td>
        </tr> -->
        </table>
      </td>
    </tr>
    <tr>
      <td height="28" colspan="2"> </td>
    </tr>
    <tr align="center">
      <td style="font-family: Verdana, Geneva, sans-serif; font-size: 13px;bottom: 0;
      position: fixed;left: 22%;" colspan="2" align="center">
        <strong>Chiklee Water Park</strong>
        <br />
        Rangpur
        <br />
        Tel: +88019678225533 | Email: service@nature-aquatics.com
        <br />
        Powered By ZIT.
        <!-- <br />
      VAT Registration No. 021021021 | ATOL No. 1234 -->
      </td>
    </tr>
    <tr>
      <td height="28" colspan="2"> </td>
    </tr>
  </table>


  <script type="text/javascript">
    window.print();
    window.onafterprint = window.close;
  </script>
</body>

</html>