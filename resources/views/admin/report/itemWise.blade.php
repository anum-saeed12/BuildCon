@extends('layouts.panel')

@section('breadcrumbs')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{$title}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}">Home</a></li>
                        <li class="breadcrumb-item">Item Wise</li>
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
                <div class="col-md-3">
                    <form class="form-horizontal" action="{{ route('report.itemwise.admin') }}" method="GET" id="categorySelect">
                        <label>Category Name</label>
                        <select name="category_id" class="form-control mb-3" id="category_id" onchange="$('#categorySelect').submit()">
                            <option selected="selected" value>Select</option>
                            @foreach ($categorys as $category)
                                <option value="{{ $category->id }}"{!! $category->id==request('category_id')?' selected':'' !!}>{{ ucfirst($category->category_name) }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col">
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
                                <div>Total Items: <b>{{ $data->total() }}</b></div>
                            </div>
                            <div class="col-md-6 text-right pr-md-4">
                                <div class="mr-2" style="display:inline-block;vertical-align:top;">
                                    <div class="input-group">
                                        <input type="text" id="myInput" onkeyup="myFunction()" placeholder=" Quick find" class="form-control"
                                               aria-label="Search">
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary" type="button"><i
                                                    class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-compact">
                                <thead>
                                <tr>
                                    <th>Sr.No.</th>
                                    <th class="pl-0">Category Name</th>
                                    <th class="pl-0">Item Name</th>
                                    <th class="pl-0">Brand Name</th>
                                    <th class="pl-0">Price</th>
                                    <th class="pl-0">Unit</th>
                                </tr>
                                </thead>
                                <tbody id="myTable">
                                @forelse($data as $item)
                                    <tr style="cursor:pointer" class="no-select" data-toggle="modal">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ ucfirst($item->category_name) }}</td>
                                        <td>{{ ucfirst($item->item_name) }}</td>
                                        <td>{{ ucfirst($item->brand_name) }}</td>
                                        <td>{{ $item->price }}</td>
                                        <td>{{ $item->unit }}</td>
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
                </div>
            </div>
        </div>
    </section>
@stop

@section('extras')
    <script>
        $(document).ready(function(){
            $("#myInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#myTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            $('.c-tt').tooltip();
        });
    </script>
@stop
