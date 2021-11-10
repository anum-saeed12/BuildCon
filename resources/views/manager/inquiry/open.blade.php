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
                        <li class="breadcrumb-item">Open Inquiry</li>
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
                @include('manager.inquiry.components.filters')
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
                                <a href="{{ route('inquiry.add.manager') }}" class="btn btn-success"><i class="fa fa-plus-circle mr-1"></i> Add New</a>

                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-compact">
                                <thead>
                                <tr>
                                    <th>Sr.No.</th>
                                    <th class="pl-0">Client</th>
                                    <th class="pl-0">Project</th>
                                    <th class="pl-0">Items Description</th>
                                    <th class="pl-0">Amount</th>
                                    <th class="pl-0">Sales Person</th>
                                    <th class="pl-0">Date</th>
                                    <th class="pl-0">Submission Timeline</th>
                                </tr>
                                </thead>
                                <tbody id="myTable">
                                @foreach($inquires as $inquiry)
                                    <tr style="cursor:pointer" class="no-select" data-toggle="modal"
                                        data-href="{{ route('inquiry.view.manager',$inquiry->ids) }}">
                                        <td><a href="{{ route('inquiry.view.manager',$inquiry->ids) }}">{{ $loop->iteration }}</td>
                                        <td><a href="{{ route('inquiry.view.manager',$inquiry->ids) }}">{{ ucfirst($inquiry->customer_name) }}</td>
                                        <td><a href="{{ route('inquiry.view.manager',$inquiry->ids) }}">{{ ucfirst($inquiry->project_name) }}</td>
                                        <td><a href="{{ route('inquiry.view.manager',$inquiry->ids) }}">{{ ucfirst($inquiry->item_description) }}</td>
                                        <td><a href="{{ route('inquiry.view.manager',$inquiry->ids) }}">{{ $inquiry->total }}</td>
                                        <td><a href="{{ route('inquiry.view.manager',$inquiry->ids) }}">{{ $inquiry->name }}</td>
                                        <td><a href="{{ route('inquiry.view.manager',$inquiry->ids) }}">{{ ucfirst($inquiry->date) }}</td>
                                        <td><a href="{{ route('inquiry.view.manager',$inquiry->ids) }}">{{ ucfirst($inquiry->timeline) }}</td>
                                        <td class="text-right p-0">
                                            <a class="bg-warning list-btn"  href="{{ route('quotation.generate.manager',$inquiry->ids) }}" title="Download Files"><i class="fas fa-download" aria-hidden="false"></i></a>
                                            @if($inquiry->inquiry_status=='open')<a class="bg-success list-btn"  href="{{ route('quotation.generate.manager',$inquiry->ids) }}" title="Generate Quotation"><i class="fas fa-file" aria-hidden="false"></i></a>@endif
                                            <a class="bg-primary list-btn"  href="{{ route('inquiry.edit.manager',$inquiry->ids) }}" title="Edit"><i class="fas fa-tools" aria-hidden="false"></i></a>
                                            <a class="bg-danger list-btn"  href="{{ route('inquiry.delete.manager',$inquiry->ids) }}"  title="Delete"><i class="fas fa-trash-alt" aria-hidden="false"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse">
                        {!! $inquires->links('pagination::bootstrap-4') !!}
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
