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
                        <li class="breadcrumb-item">Inquiry Date</li>
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
            <form action="{{ route('inquiry.datewise.admin') }}" method="GET">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="date_start" class="normal">From</label><br/>
                        <input type="date" class="form-control" name="date_start" value="{{ request('date_start') ?? date('Y-m-d') }}"/>
                    </div>
                    <div class="col-md-3">
                        <form class="form-horizontal" action="{{ route('inquiry.datewise.admin') }}" method="GET" id="date_range">
                            <label for="date_end" class="normal">To</label><br/>
                            <input type="date" class="form-control" name="date_end" value="{{ request('date_end') ?? date('Y-m-d') }}"/>
                        </form>
                    </div>
                    <div class="col">
                        <label for="" class="normal">&nbsp;</label><br/>
                        <button type="submit" class="btn btn-info">Search</button>
                    </div>
                </div>
            </form>

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
                                @if(count($data) > 0)
                                    <a href="{{ route('datewise.reportPDF.admin',[request('date_start') ?? date('Y-m-d'), request('date_end') ?? date('Y-m-d') ]) }}" class="btn btn-success"><i class="fa fa-plus-circle mr-1"></i>Download PDf</a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-compact">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="pl-0">Inquiry Id</th>
                                    <th class="pl-0">User</th>
                                    <th class="pl-0">Customer</th>
                                    <th class="pl-0">Project</th>
                                    <th class="pl-0">Total Items</th>
                                    <th class="pl-0">Date</th>
                                    <th class="pl-0">Timeline</th>
                                    <th class="pl-0">Created</th>
                                </tr>
                                </thead>
                                <tbody id="myTable">
                                @forelse($data as $item)
                                    <tr style="cursor:pointer" class="no-select" data-toggle="modal">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ substr($item->inquiry,0,7) }}</td>
                                        {{--      <td>{{ $item->inquiry }}</td>--}}
                                        <td>{{ ucfirst($item->username) }} ({{ $item->user_role }})</td>
                                        <td>{{ ucfirst($item->customer_name) }}</td>
                                        <td>{{ ucfirst($item->project_name) }}</td>
                                        <td><b>{{ $item->total_items }}</b> items</td>
                                        <td>{{ \Carbon\Carbon::createFromDate($item->date)->format('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::createFromDate($item->timeline)->format('d M Y') }}</td>
                                        <td>{{ $item->created_at->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="99" class="py-3 text-center">No quotes found</td>
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
