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
                                    <th class="pl-0">Sales Person</th>
                                    <th class="pl-0">Date</th>
                                    <th class="pl-0">Submission Timeline</th>
                                </tr>
                                </thead>
                                <tbody id="myTable">
                                @forelse($inquires as $inquiry)
                                    <tr style="cursor:pointer" class="no-select" data-toggle="modal"
                                        data-href="{{ route('inquiry.view.manager',$inquiry->id) }}">
                                        <td><a href="{{ route('inquiry.view.manager',$inquiry->id) }}">{{ $loop->iteration + intval(($inquires->currentPage() - 1) * $inquires->count())}}</td>
                                        <td><a href="{{ route('inquiry.view.manager',$inquiry->id) }}">{{ ucwords($inquiry->customer_name) }}</td>
                                        <td><a href="{{ route('inquiry.view.manager',$inquiry->id) }}">{{ ucwords($inquiry->project_name) }}</td>
                                        <td><a href="{{ route('inquiry.view.manager',$inquiry->id) }}">{{ ucwords($inquiry->username) }}</td>
                                        <td><a href="{{ route('inquiry.view.manager',$inquiry->id) }}">{{ \Carbon\Carbon::parse($inquiry->date)->format('d-M-Y') }}</td>
                                        <td><a href="{{ route('inquiry.view.manager',$inquiry->id) }}">{{ ucwords($inquiry->timeline) }}</td>
                                        <td class="text-right p-0">
                                            <a class="bg-warning list-btn" href="{{ route('inquiry.documents.manager', $inquiry->id) }}"
                                               data-doc="Documents for {{ ucfirst($inquiry->customer_name) }} - {{ ucfirst($inquiry->project_name) }}"
                                               onclick="$('#downloadableFilesTitle').html($(this).data('doc'));$('#downloadableFilesHolder').html('Loading wait please...');$('#downloadableFilesHolder').load($(this).attr('href'));"
                                               title="Download Files" data-toggle="modal" data-target="#downloadable-files"  data-placement="bottom">
                                                <i class="fas fa-download" aria-hidden="false"></i>
                                            </a>
                                            @if($inquiry->inquiry_status=='open')<a class="bg-success list-btn" data-toggle="tooltip" data-placement="bottom" href="{{ route('quotation.generate.manager',$inquiry->id) }}" title="Generate Quotation"><i class="fas fa-file" aria-hidden="false"></i></a>@endif
                                            <a class="bg-primary list-btn" data-toggle="tooltip" data-placement="bottom" href="{{ route('inquiry.edit.manager',$inquiry->id) }}" title="Edit"><i class="fas fa-tools" aria-hidden="false"></i></a>
                                            <a class="bg-danger list-btn" data-toggle="tooltip" data-placement="bottom" href="{{ route('inquiry.delete.manager',$inquiry->id) }}"  title="Delete"><i class="fas fa-trash-alt" aria-hidden="false"></i></a>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="py-3 text-center">No open inquiries found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse">
                        {!! $inquires->appends($_GET)->links('pagination::bootstrap-4') !!}
                    </div>
                    <div class="modal" id="downloadable-files" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-title" id="downloadableFilesTitle"></div>
                            <div class="modal-content p-3" id="downloadableFilesHolder">
                                Something here
                            </div>
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
            $('#downloadable-files').on('show.bs.modal show', function (event) {
                alert(10)
                var button = $(event.relatedTarget) // Button that triggered the modal
                var title = button.data('title')
                var modal = $(this)
                alert(10)
                modal.find('.modal-title').text(title)
                $('#downloadableFilesHolder').load(button.attr('href'))
            })
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
