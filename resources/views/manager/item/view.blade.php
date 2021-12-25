@extends('layouts.panel')

@section('breadcrumbs')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1>{{$title}} <a href="{{ route('item.export') }}" class="btn btn-info btn-sm ml-2"><i class="fas fa-file-export mr-1"></i>Export</a></h1>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.manager') }}">Home</a></li>
                        <li class="breadcrumb-item">Item</li>
                        <li class="breadcrumb-item active">{{$title}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
@stop


@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @if(session()->has('success'))
                        <div class="callout callout-success" style="color:green">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    @if(session()->has('error'))
                        <div class="callout callout-danger" style="color:red">
                            {{ session()->get('error') }}
                        </div>
                    @endif
                    <div class="card">
                        <div class="row mb-3 mt-3 ml-3">
                            <div class="col-md-6">
                                <form action="" method="GET" id="perPage">
                                    <label for="perPageCount">Show</label>
                                    <select id="perPageCount" name="count" onchange="$('#perPage').submit();"
                                            class="input-select mx-2">
                                        <option value="15"{{ request('count')=='15'?' selected':'' }}>15 rows</option>
                                        <option value="25"{{ request('count')=='25'?' selected':'' }}>25 rows</option>
                                        <option value="50"{{ request('count')=='50'?' selected':'' }}>50 rows</option>
                                        <option value="100"{{ request('count')=='100'?' selected':'' }}>100 rows</option>
                                    </select>
                                </form>
                            </div>
                            <div class="col-md-6 text-right pr-md-4">
                                <form method="Get" action="" style="display:inline-block;vertical-align:top;" class="mr-2">
                                    <div class="input-group">
                                        <input type="text" id="myInput" onkeyup="myFunction()" placeholder=" Search" class="form-control"
                                               aria-label="Search">
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary" type="submit"><i
                                                    class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <a href="{{ route('item.add.manager') }}" class="btn btn-success"><i class="fa fa-plus-circle mr-1"></i> Add New</a>
                                <form action="{{ route('item.import') }}" method="post" enctype="multipart/form-data" style="display:inline;" id="importItemForm">
                                    @csrf
                                    <label href="{{ route('item.add.manager') }}" class="btn btn-primary ml-2 mb-0" for="importItemsFile">
                                        <input style="display:none;" type="file" id="importItemsFile" name="itemsFile" onchange="$('#importItemForm').submit()"/>
                                        <i class="fas fa-file-import"></i>
                                        Import
                                    </label>
                                </form>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-compact">
                                <thead>
                                <tr>
                                    <th>Sr.No.</th>
                                    <th class="pl-0">Item Name</th>
                                    {{--<th class="pl-0">Item Picture</th>--}}
                                    <th class="pl-0">Brand</th>
                                    <th class="pl-0">Category</th>
                                    <th class="pl-0">Item Description</th>
                                    <th class="pl-0">Unit</th>
                                    <th class="pl-0">Price</th>
                                    <th class="pl-0">Weight</th>
                                    <th class="pl-0">Height</th>
                                    <th class="pl-0">Width</th>
                                </tr>
                                </thead>
                                <tbody id="myTable">
                                @forelse($items as $item)
                                    <tr style="cursor:pointer" class="no-select" data-toggle="modal"
                                        data-href="{{ route('item.edit.manager',$item->item_id) }}">
                                        <td><a href="{{ route('item.edit.manager',$item->item_id) }}">{{ $loop->iteration + intval(($items->currentPage() - 1) * $items->count()) }}</td>
                                        <td><a href="{{ route('item.edit.manager',$item->item_id) }}">{{ucfirst($item->item_name)}}</td>
                                        {{--<td><a href="{{ asset('storage/images/'.$item->item_id) }}" target="_blank">
                                                <div class="list-img-thumbnail" style="background-image:url('{{ asset('storage/images/'.$item->picture) }}');"></div>
                                            </a>
                                        </td>--}}
                                        <td><a href="{{ route('item.edit.manager',$item->item_id) }}">{{ucfirst($item->brand_name)}}</td>
                                        <td><a href="{{ route('item.edit.manager',$item->item_id) }}">{{ucfirst($item->category_name)}}</td>
                                        <td><a href="{{ route('item.edit.manager',$item->item_id) }}">{{ucfirst($item->item_description)}}</td>
                                        <td><a href="{{ route('item.edit.manager',$item->item_id) }}">{{ucfirst($item->unit)}}</td>
                                        <td><a href="{{ route('item.edit.manager',$item->item_id) }}">{{$item->price}}</td>
                                        <td><a href="{{ route('item.edit.manager',$item->item_id) }}">{{$item->weight}}</td>
                                        <td><a href="{{ route('item.edit.manager',$item->item_id) }}">{{$item->height}}</td>
                                        <td><a href="{{ route('item.edit.manager',$item->item_id) }}">{{$item->width}}</td>
                                        <td class="text-right p-0">
                                            <a class="bg-info list-btn" data-toggle="tooltip" data-placement="bottom" data-html="true" href="{{ asset('storage/images/'.$item->picture) }}" title="<img src='{{ asset('storage/images/'.$item->picture) }}' style='max-width:150px;'/>"><i class="fas fa-image" aria-hidden="false"></i></a>
                                            <a class="bg-primary list-btn" data-toggle="tooltip" data-placement="bottom" href="{{ route('item.edit.manager',$item->item_id) }}" title="Edit"><i class="fas fa-tools" aria-hidden="false"></i></a>
                                            <a class="bg-danger list-btn" data-toggle="tooltip" data-placement="bottom" href="{{ route('item.delete.manager',$item->item_id) }}"  title="Delete"><i class="fas fa-trash-alt" aria-hidden="false"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-3 text-center">No items found</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse">
                      {!! $items->appends($_GET)->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('extras')
    <script type="text/javascript">
        $(document).ready(function(){
            $("#myInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#myTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
@stop
