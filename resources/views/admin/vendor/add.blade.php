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
            <div class="col-md-12">
                <div class="card card-info">
                    <form class="form-horizontal" action="{{ route('vendor.store.admin') }}" method="POST">
                        @csrf
                        <div class="card-body pb-0 ">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="vendor_name">Vendor Name</label><br/>
                                    <input type="text" name="vendor_name" class="form-control" id="vendor_name"
                                           value="{{ old('vendor_name') }}">
                                    <div class="text-danger">@error('vendor_name'){{ $message }}@enderror</div>
                                </div>
                                <div class="col-md-4">
                                    <label for="attended_person">Attention Person</label><br/>
                                    <input type="text" name="attended_person" class="form-control" id="attended_person"
                                            value="{{ old('attended_person') }}">
                                    <div class="text-danger">@error('attended_person'){{ $message }}@enderror</div>
                                </div>
                                <div class="col-md-4">
                                    <label for="country">Country</label><br/>
                                    <input type="text" name="country" class="form-control" id="country"
                                            value="{{ old('country') }}">
                                    <div class="text-danger">@error('country'){{ $message }}@enderror</div>
                                </div>
                            </div>
                        <div class="row">
                            <div class="col-md-8">
                                <label for="address">Address</label><br/>
                                <textarea class="form-control" name="address" id="address"></textarea>
                                <div class="text-danger">@error('address'){{ $message }}@enderror</div>
                            </div>
                        </div>
                            <div class="row mt-2">
                                <div class="col mb-3 text-center">
                                    <button type="submit" class="btn btn-default">Cancel</button>
                                    <span class="mr-3"></span>
                                    <button type="submit" class="btn btn-info">{{$title}}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@stop

