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
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.sale') }}">Home</a></li>
                        <li class="breadcrumb-item active">{{$title}}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white">
                        <div class="inner">
                            <h3>{{ $total_inquiries->total }}</h3>
                            <p>Total Inquiries</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file nav-icon text-gray"></i>
                        </div>
                        <a href="{{ route('inquiry.list.sale') }}" class="small-box-footer bg-gray" style="color:white!important;">View Inquiries <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white">
                        <div class="inner">
                            <h3>{{ $total_open_inquiries->total }}</h3>
                            <p>Open Inquiries</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-pen-square nav-icon text-gray"></i>
                        </div>
                        <a href="{{ route('inquiry.open.sale') }}" class="small-box-footer bg-gray" style="color:white!important;">View Open Inquiries <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white">
                        <div class="inner">
                            <h3>{{ $total_quotation->total }}</h3>
                            <p>Submitted Quotation</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-receipt  nav-icon text-info"></i>
                        </div>
                        <a href="{{ route('quotation.list.sale') }}" class="small-box-footer bg-info" style="color:white!important;">View Submitted Quotation <i class="fas"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

