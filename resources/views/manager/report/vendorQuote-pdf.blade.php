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
            font-size: 12px;
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
            <h2><b>Vendor Report</b></h2>
        </td>
    </tr>
</table>

<br />

<table width="100%" style="text-align: center">
    <thead style="background-color: #eae8e4;">
    <tr>
        <th>#</th>
        <th class="pl-0">Vendor</th>
        <th class="pl-0">User</th>
        <th class="pl-0">Project</th>
        <th class="pl-0">Category</th>
        <th class="pl-0">Brand</th>
        <th class="pl-0">Quotation#</th>
        <th class="pl-0">Rate</th>
        <th class="pl-0">Total Amount</th>
        <th class="pl-0">Created</th>
    </tr>
    </thead>
    <tbody id="myTable">
    @forelse($data as $k => $item)
        <tr style="cursor:pointer" class="no-select" data-toggle="modal">
            <td>{{ $loop->iteration }}</td>
            <td>{{ ucfirst($item->vendor_name) }}</td>
            <td>{{ ucfirst($item->username) }} ({{ $item->user_role }})</td>
            <td>{{ ucfirst($item->project_name) }}</td>
            <td>{{ ucfirst($item->category_name) }}</td>
            <td>{{ ucfirst($item->brand_name) }}</td>
            <td>{{ str_pad($item->quotation_id, 5, '0', STR_PAD_LEFT) }}</td>
            @php
                $k = isset($k)?$k:0;
                $currentRate = isset($item->rate)?floatval($item->rate):0.00;
                if (!isset($oldRate)) $oldRate = isset($data[$k+1]->rate)?floatval($data[$k+1]->rate):0.00;
                $pre = '';
                if($oldRate<$currentRate){
                    $style = "color:red;";
                    $pre = "+";
                }
                if($oldRate == $currentRate){
                    $style = "color:#000;";
                    echo "";
                }
                if($oldRate > $currentRate){
                    $style = "color:green;";
                    $pre = "-";
                }
                $oldRate = isset($data[$k]->rate)?floatval($data[$k]->rate):0.00;
            @endphp
            <td style="{{ $style }}">{{$pre}}{{ $item->rate }} {{ $item->currency }}</td>
            <td>{{ $item->amount }} {{ $item->currency }}</td>
            <td>{{ $item->created_at->format('d M Y') }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="99" class="py-3 text-center">No quotes found</td>
        </tr>
    @endforelse
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
