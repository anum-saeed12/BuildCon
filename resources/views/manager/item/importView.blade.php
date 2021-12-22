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
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.manager') }}">Home</a></li>
                        <li class="breadcrumb-item">Import Item</li>
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
                                        <input type="text" id="myInput" onkeyup="myFunction()" placeholder=" Quick find" class="form-control">
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary" type="submit"><i
                                                    class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <a href="{{ route('item.add.manager') }}" class="btn btn-danger"><i class="fa fa-times mr-1"></i> Cancel</a>
                                <form action="{{ route('item.import.approve') }}" method="post" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="batch_id" value="{{ $batch_id }}">
                                    <button type="submit" class="btn btn-success ml-2 mb-0">
                                        <i class="fa fa-arrow-circle-right mr-1"></i>
                                        Continue
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-compact">
                                <thead>
                                <tr>
                                    <th>Sr.No.</th>
                                    <th class="pl-0">Item Name</th>
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
                                @forelse($imported_data as $item)
                                    <tr style="cursor:pointer" class="no-select" data-toggle="modal"
                                        data-href="#">
                                        <td>{{ $loop->iteration + intval(($imported_data->currentPage() - 1) * $imported_data->count()) }}</td>
                                        <td>{{ucfirst($item->item_name)}}</td>
                                        <td>{{ucfirst($item->brand_name)}}</td>
                                        <td>{{ucfirst($item->category_name)}}</td>
                                        <td>{{ucfirst($item->item_description)}}</td>
                                        <td>{{ucfirst($item->unit)}}</td>
                                        <td>{{$item->price}}</td>
                                        <td>{{$item->weight}}</td>
                                        <td>{{$item->height}}</td>
                                        <td>{{$item->width}}</td>
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
                      {!! $imported_data->appends($_GET)->links('pagination::bootstrap-4') !!}
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
