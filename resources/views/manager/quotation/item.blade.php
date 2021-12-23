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
                        <li class="breadcrumb-item"><a href="{{ route('customerquotation.list.manager') }}">Quotation</a></li>
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
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('quotation.pdfinquiry.manager',$quotation[0]->unique) }}" type="submit" class="btn btn-info toastrDefaultSuccess btn-sm" target="btnActionIframe"><i class="far fa-file-alt mr-1"></i> Create Quotation Pdf</a>
                <iframe name="btnActionIframe" style="display:none;" onload="setTimeout(function(){this.src=''},1000)"></iframe>
            </div>
        </div>

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
                    <div class="card-body p-0">
                        <div class="invoice p-3 mb-3">
                            <div class="row">
                                <div class="col-12">
                                   <strong> <h2 style="text-align: center; text-decoration: underline" class="mb-4">
                                        {{ ucwords('Build Con') }}
                                    </h2></strong>
                                    <h4 style="text-align: center" class="mb-4">
                                        {{ ucwords('Quotation') }}
                                    </h4>
                                </div>
                            </div>
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    <address>
                                        <p><b>Ref: </b>{{ strtoupper(substr($quotation[0]->quotation,0,4)) }}-{{ strtoupper(substr($quotation[0]->quotation,4,4)) }}-{{ \Carbon\Carbon::createFromTimeStamp(strtotime($quotation[0]->created_at))->format('dm') }}-{{ \Carbon\Carbon::createFromTimeStamp(strtotime($quotation[0]->created_at))->format('Y') }}</p>
                                        <p><b>Attention: </b>{{ ucwords($quotation[0]->attention_person) }}</p>
                                        <p><b>Customer Name: </b>{{ ucwords($quotation[0]->customer_name) }}</p>
                                        <p><b>Project Name: </b>{{ ucwords($quotation[0]->project_name) }}</p>
                                    </address>
                                </div>
                                <div class="offset-6 col-sm-2 invoice-col">
                                    <address>
                                        <p><b>Date: </b>{{ ucwords(\Carbon\Carbon::createFromTimeStamp(strtotime($quotation[0]->date))->format('Y-m-d')) }}</p>
                                    </address>
                                </div>
                            </div>
                            <div class="row" >
                                <div class="col-12 table-responsive">
                                    <table class="table table-striped table-sm">
                                        <thead>
                                        <tr>
                                            <th>Sr.no</th>
                                            <th>Item Name</th>
                                            <th>Item Description</th>
                                            <th>Brand</th>
                                            <th>Quantity</th>
                                            <th>Unit</th>
                                            <th>Unit Price ( {{ ucwords($quotation[0]->currency) }} )</th>
                                            <th>Dis.Price ( {{ ucwords($quotation[0]->currency) }} )</th>
                                            <th>Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($quotation as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ ucwords($item->item_name) }}</td>
                                            <td>{{ ucwords($item->item_description) }}</td>
                                            <td>{{ ucwords($item->brand_name) }}</td>
                                            <td>{{ ucwords($item->quantity) }}</td>
                                            <td>{{ ucwords($item->unit) }}</td>
                                            <td>{{ ucwords($item->rate) }}</td>
                                            <td>{{ ucwords($item->discount_rate) }}</td>
                                            <td>{{ ucwords($item->amount) }}</td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6"></div>
                                <div class="col-6">
                                    <p class="lead"></p>
                                    <div class="table-responsive table-sm">
                                        <table class="table">
                                           <tr>
                                                <th style="width:50%">Discount:</th>
                                               <td>{{ $item->discount }}<b>%</b></td>
                                           </tr>
                                            <tr>
                                                <th style="width:50%">Total Amount:</th>
                                                <td><b>{{ ucwords($item->currency) }}</b> {{ $item->total }}</td>
                                           </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
