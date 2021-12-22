<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>{{ $quotation->creation }}</title>
    <style type="text/css">
        * {font-family:  Verdana, Arial, Helvetica, sans-serif;}
        table {font-size: 12px;}
        table th {background:#eee;color:#000;}
        table th, table td {padding:2px 5px;}
        tfoot tr td {font-weight: bold;font-size: small;}
        hr.hr1 {border: 15px solid #eae8e4;}
    </style>
</head>
<hr class="hr1" />
<body>
<table width="100%">
    <tr>
        <td align="center">
            <h1><b>Build Con</b></h1>
            <h2><b>Quotation Invoice</b></h2>
        </td>
    </tr>
</table>

<table width="100%">
    <tr>
        <td colspan="2">
            <p><b>Ref: </b>{{ strtoupper(substr($quotation->inquiry,0,4)) }}-{{ strtoupper(substr($quotation->inquiry,4,4)) }}-{{ \Carbon\Carbon::createFromTimeStamp(strtotime($quotation->created_at))->format('dm') }}-{{ \Carbon\Carbon::createFromTimeStamp(strtotime($quotation->created_at))->format('Y') }}</p>
            <p><b>Attention: </b>{{ ucwords($quotation->attention_person) }}</p>
            <p><b>Customer Name: </b>{{ ucwords($quotation->customer_name) }}</p>
            <p><b>Project Name: </b>{{ ucwords($quotation->project_name) }}</p>
        </td>
        <td colspan="4" align="top"><strong>Date #{{ $quotation->creation }}</strong></td>
    </tr>
</table>

<table width="100%" style="text-align:left;" border="1">
    <thead>
        <tr>
            <th>Sr.no</th>
            <th>Item Description</th>
            <th>Brand</th>
            <th>Quantity</th>
            <th>Unit</th>
            <th>Unit Price ({{ ucwords($quotation->currency) }})</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($quotation->items as $item)
            @if(isset($category))
                @if($category!=$item->category_id)
                    <tr>
                        <th colspan="9" style="text-align:left;text-transform:uppercase;"><b>{{ $item->category_name }}</b></th>
                    </tr>
                    @php $category = $item->category_id; @endphp
                @endif
            @else
                <tr>
                    <th colspan="9" style="text-align:left;text-transform:uppercase;"><b>{{ $item->category_name }}</b></th>
                </tr>
                @php $category = $item->category_id; @endphp
            @endif
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <b>{{ ucwords($item->item_name) }}</b><br/>
                    {{ ucwords($item->item_description) }}
                </td>
                <td>{{ ucwords($item->brand_name) }}</td>
                <td>{{ ucwords($item->quantity) }}</td>
                <td>{{ ucwords($item->unit) }}</td>
                <td>{{ ucwords($item->rate) }}</td>
                <td>{{ ucwords($item->amount) }}</td>
            </tr>
        @endforeach
        <tr style="">
            <th colspan="5" style="text-align:right;">Total:</th>
            <td colspan="2" style="text-align:right;"><b>{{ $quotation->currency }} {{number_format($quotation->total )}}</b></td>
        </tr>
    </tbody>
</table>



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
