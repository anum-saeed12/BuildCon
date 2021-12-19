<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Item Report</title>
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
            <h2><b>Item Report</b></h2>
        </td>
    </tr>
</table>

<br />

<table width="100%" style="text-align: center">
    <thead style="background-color: #eae8e4;">
    <tr>
        <th>Sr.No.</th>
        <th class="pl-0">Category Name</th>
        <th class="pl-0">Item Name</th>
        <th class="pl-0">Brand Name</th>
        <th class="pl-0">Price</th>
        <th class="pl-0">Unit</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        <tr style="cursor:pointer" class="no-select" data-toggle="modal">
            <td>{{ $loop->iteration }}</td>
            <td>{{ ucfirst($item->category_name) }}</td>
            <td>{{ ucfirst($item->item_name) }}</td>
            <td>{{ ucfirst($item->brand_name) }}</td>
            <td>{{ $item->price }}</td>
            <td>{{ $item->unit }}</td>
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
