<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Vendor Report</title>
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
            <h1><b>Build Con</b></h1>
            <h2><b>Vendor Report</b></h2>
        </td>
    </tr>
</table>

<br />

<table width="100%" style="text-align: left">
    <thead style="background-color: #eae8e4;">
    <tr>
        <th>Sr.No.</th>
        <th class="pl-0">Vendor Name</th>
        <th class="pl-0">Submitted By</th>
        <th class="pl-0">Item Name</th>
        <th class="pl-0">Brand Name</th>
        <th class="pl-0">Quotation Ref#</th>
        <th class="pl-0">Rate</th>
        <th class="pl-0">Rate difference</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $quote)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ ucfirst($quote->vendor_name) }}</td>
            <td>{{ ucfirst($quote->name) }}</td>
            <td>{{ ucfirst($quote->item_name) }}</td>
            <td>{{ ucfirst($quote->brand_name) }}</td>
            <td>{{ ucfirst($quote->quotation_ref) }}</td>
            <td>{{ number_format($quote->rate) }}</td>
            <td>{{ number_format($quote->amount) }}</td>
        </tr>
    @endforeach
    </tbody>

</table>
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
