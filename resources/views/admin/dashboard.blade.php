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
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.client') }}">Home</a></li>
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

                {{--Employee BOX--}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white">
                        <div class="inner">
                            <h3>#</h3>
                            <p>Employees</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users nav-icon text-danger"></i>
                        </div>
                        <a href="#" class="small-box-footer bg-danger" style="color:white!important;">View Employees <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                {{--CLIENT BOX--}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white">
                        <div class="inner">
                            <h3>#</h3>
                            <p>Inventory</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-boxes nav-icon text-success"></i>
                        </div>
                        <a href="#" class="small-box-footer bg-success" style="color:white!important;">View Inventory <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                {{--CLIENT BOX--}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white">
                        <div class="inner">
                            <h3>#</h3>
                            <p>Open Inquiries</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file nav-icon text-gray"></i>
                        </div>
                        <a href="#" class="small-box-footer bg-gray" style="color:white!important;">View Open Inquirires <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white">
                        <div class="inner">
                            <h3>#</h3>
                            <p>Submitted Quotation</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-receipt  nav-icon text-info"></i>
                        </div>
                        <a href="#" class="small-box-footer bg-info" style="color:white!important;">View Submitted Quotation <i class="fas"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

