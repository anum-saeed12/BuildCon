<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Inquiry Sale Person Report</title>
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
            <h2><b>Inquiry Sale Person Report</b></h2>
        </td>
    </tr>
</table>

<br />
<table width="100%"style="text-align: center">
    <thead style="background-color: #eae8e4;">
    <tr>
        <th>#</th>
        <th class="pl-0">Inquiry</th>
        <th class="pl-0">User</th>
        <th class="pl-0">Customer</th>
        <th class="pl-0">Project</th>
        <th class="pl-0">Total Items</th>
        <th class="pl-0">Start</th>
        <th class="pl-0">Timeline</th>
        <th class="pl-0">Created</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        <tr style="cursor:pointer" class="no-select" data-toggle="modal">
            <td>{{ $loop->iteration }}</td>
            <td>{{ substr($item->inquiry,0,7) }}</td>
            <td>{{ ucfirst($item->username) }} ({{ $item->user_role }})</td>
            <td>{{ ucfirst($item->customer_name) }}</td>
            <td>{{ ucfirst($item->project_name) }}</td>
            <td><b>{{ $item->total_items }}</b> items</td>
            <td>{{ \Carbon\Carbon::createFromDate($item->date)->format('d M Y') }}</td>
            <td>{{ \Carbon\Carbon::createFromDate($item->timeline)->format('d M Y') }}</td>
            <td>{{ $item->created_at->format('d M Y') }}</td>
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
