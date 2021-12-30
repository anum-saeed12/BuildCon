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
                        <li class="breadcrumb-item">Vendor</li>
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
                    <form class="form-horizontal" action="{{ route('vendorQuotes.report.admin') }}" method="GET" id="itemSelect">
                        <label>Item Name</label>
                        <select name="item" class="form-control mb-3" id="item_id" onchange="$('#itemSelect').submit()">
                            <option selected="selected" value>Select</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->item_name }}"{!! $item->item_name==request('item')?' selected':'' !!}>{{ ucfirst($item->item_name) }}</option>
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
                                <form action="." method="GET" id="perPage">
                                    <label for="perPageCount">Show</label>
                                    <select id="perPageCount" name="count" onchange="$('#perPage').submit();"
                                            class="input-select mx-2">
                                        <option value="15"{{ request('count')=='15'?' selected':'' }}>15 rows</option>
                                        <option value="25"{{ request('count')=='25'?' selected':'' }}>25 rows</option>
                                        <option value="50"{{ request('count')=='50'?' selected':'' }}>50 rows</option>
                                        <option value="100"{{ request('count')=='100'?' selected':'' }}>100 rows</option>
                                    </select>
                                </form>
                                <div>Total Quotes: <b>{{ $data->total() }}</b></div>
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
                                @if(request('item'))
                                <a href="{{ route('vendorQuotes.reportPDF.admin',request('item')) }}" class="btn btn-success"><i class="fa fa-plus-circle mr-1"></i>Download PDf</a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-compact">
                                <thead>
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
                                        <td>{{ $item->quotation_ref }}</td>
                                        <td>
                                            @php
                                            $k = isset($k)?$k:0;
                                            $currentRate = isset($item->rate)?floatval($item->rate):0.00;
                                            if (!isset($oldRate)) $oldRate = isset($data[$k+1]->rate)?floatval($data[$k+1]->rate):0.00;
                                            if($oldRate<$currentRate){
                                                $class = "text-danger";
                                                echo "<i class='fa fa-caret-up text-danger'></i> ";
                                            }
                                            if($oldRate == $currentRate){
                                                $class = "text-secondary";
                                                echo "<i class='fa fa-check text-secondary'></i> ";
                                            }
                                            if($oldRate > $currentRate){
                                                $class = "text-success";
                                                echo "<i class='fa fa-caret-down text-success'></i> ";
                                            }
                                            $oldRate = isset($data[$k]->rate)?floatval($data[$k]->rate):0.00;
                                            @endphp
                                            <span class="{{ $class }} pr-2">{{ $item->rate }} {{ $item->currency }}</span>
                                        </td>
                                        <td>{{ $item->amount }} {{ $item->currency }}</td>
                                        <td>{{ $item->created_at->format('d M Y')  }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="99" class="py-3 text-center">No vendor quotes found</td>
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
