<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Quotation Report</title>
    <style type="text/css">
        * {
            font-family:  Verdana, Arial, Helvetica, sans-serif;
        }
        table {
            font-size: small;
        }
        tfoot tr td {
            font-weight: bold;
            font-size: small;
        }
        hr.hr1 {
            border: 15px solid #eae8e4;
        }
    </style>
</head>
<hr class="hr1" />
<body>
<table width="100%">
    <tr>
        <td align="center">
            <h1><b>BuildCon</b></h1>
            <h2><b>Quotation Report</b></h2>
        </td>
    </tr>
</table>

<br />
<table width="100%" style="text-align: center">
    <thead style="background-color: #eae8e4;">
    <tr>
        <th>#</th>
        <th class="pl-0">Customer Name</th>
        <th class="pl-0">Item Name</th>
        <th class="pl-0">Brand Name</th>
        <th class="pl-0">Rate</th>
        <th class="pl-0">Total Amount</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $quote)
        <tr style="cursor:pointer" class="no-select" data-toggle="modal">
            <td>{{ $loop->iteration }}</td>
            <td>{{ ucfirst($quote->customer_name) }}</td>
            <td>{{ ucfirst($quote->item_name) }}</td>
            <td>{{ ucfirst($quote->brand_name) }}</td>
            <td>{{ $quote->rate }}</td>
            <td>{{ $quote->amount }}</td>
        </tr>
    @endforeach
    </tbody>

</table>
<br><br>
<div>
    <div style="float: right;">Total Amount: <b>{!!  $data->sum('amount') !!}</b></div>
</div>
<br><br><br>
<hr class="hr1" />
</body>
</html>
<script>
    function number_format(num)
    {
        var num_parts = num.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return num_parts.join(".");
    }
</script>
