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
                        <li class="breadcrumb-item">Quotation</li>
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
                    @include('admin.quotation.components.filters')
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
                                <a href="{{ route('quotation.add.admin') }}" class="btn btn-success"><i class="fa fa-plus-circle mr-1"></i> Add New</a>

                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-compact">
                                <thead>
                                <tr>
                                    <th>Sr.No.</th>
                                    <th class="pl-0">Customer Name</th>
                                    <th class="pl-0">Project Name</th>
                                    <th class="pl-0">Competitor Name</th>
                                    <th class="pl-0">Date</th>
                                    <th class="pl-0">Amount</th>
                                    <th class="pl-0">Competitor Amount</th>
                                </tr>
                                </thead>
                                <tbody id="myTable">
                                @forelse($quotations as $quotation)
                                    <tr style="cursor:pointer" class="no-select" data-toggle="modal"
                                        data-href="#">
                                        <td><a href="{{ route('comparison.edit.admin',$quotation->id) }}">{{ $loop->iteration }}</td>
                                        <td><a href="{{ route('comparison.edit.admin',$quotation->id) }}">{{ ucfirst($quotation->customer_name) }}</td>
                                        <td><a href="{{ route('comparison.edit.admin',$quotation->id) }}">{{ ucfirst($quotation->project_name) }}</td>
                                        <td><a href="{{ route('comparison.edit.admin',$quotation->id) }}">{{ ucfirst($quotation->competitor_name) }}</td>
                                        <td><a href="{{ route('comparison.edit.admin',$quotation->id) }}">{{ \Carbon\Carbon::parse($quotation->date)->format('d-M-Y')}}</td>
                                        <td><a href="{{ route('comparison.edit.admin',$quotation->id) }}">{{ $quotation->total }}</td>
                                        <td><a href="{{ route('comparison.edit.admin',$quotation->id) }}">{{ ucfirst($quotation->total_comparison) }}</td>
                                        <td class="text-right p-0">
                                            <a class="bg-primary list-btn" href="{{ route('comparison.edit.admin',$quotation->id) }}" title="Edit"><i class="fas fa-tools" aria-hidden="false"></i></a>
                                            <a class="bg-danger list-btn"  href="{{ route('comparison.delete.admin',$quotation->id) }}" title="Delete"><i class="fas fa-trash-alt" aria-hidden="false"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-3 text-center">No quotations found</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse">
                      {!! $quotations->links('pagination::bootstrap-4') !!}
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
