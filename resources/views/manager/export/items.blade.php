<table>
    <thead>
    <tr>
        <th><b>Item name</b></th>
        <th><b>Brand name</b></th>
        <th><b>Category name</b></th>
        <th><b>Item description</b></th>
        <th><b>Price</b></th>
        <th><b>Weight</b></th>
        <th><b>Unit</b></th>
        <th><b>Width</b></th>
        <th><b>Dimension</b></th>
        <th><b>Height</b></th>
    </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
        <tr>
            <td>{{ $item->item_name }}</td>
            <td>{{ $item->brand->brand_name }}</td>
            <td>{{ $item->category->category_name }}</td>
            <td>{{ $item->item_description }}</td>
            <td>{{ $item->price }}</td>
            <td>{{ $item->weight }}</td>
            <td>{{ $item->unit }}</td>
            <td>{{ $item->width }}</td>
            <td>{{ $item->dimension }}</td>
            <td>{{ $item->height }}</td>
        </tr>
    @endforeach
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    </tbody>
</table>
