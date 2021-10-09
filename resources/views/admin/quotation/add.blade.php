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
            <div class="col-md-12">
                <div class="card card-info">
                    <form class="form-horizontal" action="#" method="POST">
                        @csrf
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="customer_id">Select Customer</label><br/>
                                    <select name="customer_id" class="form-control" id="customer_id">
                                        <option selected="selected" value>Select</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ ucfirst($customer->customer_name) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger">@error('customer_id'){{ $message }}@enderror</div>
                                </div>
                                <div class="col-md-3">
                                    <label for="project_name">Project Name</label><br/>
                                    <input type="text" name="project_name" class="form-control" id="project_name"
                                            value="{{ old('project_name') }}">
                                    <div class="text-danger">@error('project_name'){{ $message }}@enderror</div>
                                </div>

                                <div class="col-md-3">
                                    <label for="date">Date</label><br/>
                                    <div class="input-group date" id="date" data-target-input="nearest">
                                        <input type="text" id="date" name="date"
                                               class="form-control datetimepicker-input"
                                               data-target="#date" placeholder="yyyy/mm/dd"
                                               value="{{ old('date')}}"
                                               data-toggle="datetimepicker"/>
                                        <div class="input-group-append" data-target="#date"
                                             data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    <div class="text-danger">@error('date'){{ $message }}@enderror</div>
                                </div>

                            </div>
                            <br/>
                            <div class="row">
                                <div class="col-md-3 category-container">
                                    <label for="item_id">Select Item </label><br/>
                                    <select name="item_id" class="form-control" id="item_id">
                                        <option selected="selected" value>Select</option>
                                        <option value="#"></option>
                                    </select>
                                </div>
                                <div class="col-md-3 item-container">
                                    <label for="brand_id">Select Brand</label><br/>
                                    <select name="brand_id" class="form-control" id="brand_id">
                                        <option selected="selected" value>Select</option>
                                        <option value="#"></option>
                                    </select>
                                </div>
                                <div class="col-md-1 quantity-container">
                                    <label for="quantity">QTY</label><br/>
                                    <input type="text" name="quantity" class="form-control" id="quantity"
                                           value="{{ old('quantity') }}">
                                </div>
                                <div class="col-md-1 unit-container">
                                    <label for="unit">Unit</label><br/>
                                    <input type="text" name="unit" class="form-control" id="unit"
                                           value="{{ old('unit') }}">
                                </div>
                                <div class="col-md-1 rate-container">
                                    <label for="rate">Rate</label><br/>
                                    <input type="text" name="rate" class="form-control" id="rate"
                                           value="{{ old('rate') }}">
                                </div>
                                <div class="col-md-2 amount-container">
                                    <label for="amount">Amount</label><br/>
                                    <input type="text" name="amount" class="form-control" id="amount"
                                           value="{{ old('amount') }}">
                                </div>
                                <div class="col-md-1">
                                    <label for="unit">&nbsp;</label><br/>
                                    <button class="add_form_field btn btn-info"><span><i class="fas fa-plus-circle" aria-hidden="false"></i></span></button>
                                </div>
                            </div>
                            <div class="additional-products">

                            </div>
                            <br/>
                            <div class="row">
                                <div class="col-md-2 ">
                                    <label for="discount">Discount</label><br/>
                                    <input type="text" name="discount" class="form-control" id="discount"
                                           value="{{ old('discount') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="terms">Terms & Conditions</label><br/>
                                    <textarea class="form-control" name="terms" id="terms"></textarea>
                                </div>
                            </div>
                            <br/>
                            <div class="row">
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

@section('extras')
    <script type="text/javascript">
        $(document).ready(function() {

            let category_container = $('.category-container'),
                item_container = $('.item-container'),
                brand_container = $('.brand-container'),
                quantity_container = $('.quantity-container'),
                unit_container = $('.unit-container'),
                rate_container = $('.rate-container'),
                amount_container = $('.amount-container'),
                add_button = $(".add_form_field"),
                max_fields = 10,
                wrapper = $('.additional-products');

            var x = 1;
            $(add_button).click(function(e) {
                e.preventDefault();
                if (x >= max_fields) {
                    alert('You Reached the limits');
                    return false;
                }

                let $categorySelector = //'<div class="row hello">' +
                    '<div class="col-md-3 mt-3">' +
                        '<label for="item_id">Select Category</label><br/>' +
                        '<div class="row">' +
                            '<div class="col-10">' +
                                '<select name="item_id" class="form-control" id="item_id">' +
                                    '<option selected="selected" value>Select</option> <option value="#"></option>' +
                                '</select>' +
                            '</div>' +
                            '<div class="col-2">' +
                                '<a href="#" class="delete">' +
                                    '<i class="fas fa-trash-alt ml-2" aria-hidden="false"></i>' +
                                '</a>' +
                            '</div>' +
                        '</div>' +
                    '</div>';// +
                    ;// +
                    //'</div>';

                let $itemRow = '<div class="row mt-3">' +
                    '<div class="col-md-3 category-container">' +
                    '<label for="item_id">Select Item </label><br/>' +
                    '<select name="item_id" class="form-control" id="item_id">' +
                        '<option selected="selected" value>Select</option>' +
                        '<option value="#"></option>' +
                    '</select>' +
                    '</div>' +
                '<div class="col-md-3 brand-container">' +
                    '<label for="brand_id">Select Brand</label><br/>' +
                    '<select name="brand_id" class="form-control" id="brand_id">' +
                        '<option selected="selected" value>Select</option>' +
                        '<option value="#"></option>' +
                    '</select>' +
                '</div>' +
                '<div class="col-md-1 quantity-container">' +
                    '<label for="quantity">Quantity</label><br/>' +
                    '<input type="text" name="quantity" class="form-control" id="quantity" value="{{ old('quantity') }}">' +
                '</div>' +
                '<div class="col-md-1 unit-container">' +
                    '<label for="unit">Unit</label><br/>' +
                    '<input type="text" name="unit" class="form-control" id="unit" value="{{ old('unit') }}">' +
                '</div>' +
                '<div class="col-md-1 rate-container">' +
                    '<label for="rate">Unit</label><br/>' +
                    '<input type="text" name="rate" class="form-control" id="rate" value="{{ old('rate') }}">' +
                '</div>' +
                '<div class="col-md-2 amount-container">' +
                    '<label for="amount">Unit</label><br/>' +
                    '<input type="text" name="amount" class="form-control" id="amount" value="{{ old('amount') }}">' +
                '</div>' +
                '<div class="col-md-1">' +
                    '<label for="unit">&nbsp;</label><br/>' +
                    '<button class="delete btn btn-danger"><span><i class="fas fa-trash-alt" aria-hidden="false"></i></span></button>' +
                '</div>' +
            '</div>';

                x++;
                $(wrapper).append($itemRow); // add input box
            });

            $(wrapper).on("click", ".delete", function(e) {
                e.preventDefault();
                $(this).parent().parent().remove();
                x--;
            })
        });
    </script>
@stop
