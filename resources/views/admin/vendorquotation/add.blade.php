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
                        <li class="breadcrumb-item">Vendor Quotation</li>
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
                                    <label for="vendor_id">Select Vendor</label><br/>
                                    <select name="vendor_id" class="form-control" id="vendor_id">
                                        <option selected="selected" value>Select</option>
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}">{{ ucfirst($vendor->vendor_name) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger">@error('vendor_id'){{ $message }}@enderror</div>
                                </div>
                                <div class="col-md-3">
                                    <label for="quotation_id">Quotation Ref#</label><br/>
                                    <input type="text" name="quotation_id" class="form-control" id="quotation_id"
                                            value="{{ old('project_name') }}">
                                    <div class="text-danger">@error('quotation_id'){{ $message }}@enderror</div>
                                </div>

                                <div class="offset-4 col-md-2">
                                    <a href="{{ route('vendor.add.admin') }}" class="btn btn-success"><i class="fa fa-plus-circle mr-1"></i> Add Vendor</a>
                                </div>

                            </div>
                            <br/>
                            <div class="row">
                                <div class="col-md-3 category-container">
                                    <label for="category_id">Select Category</label><br/>
                                    <select name="category_id" class="form-control" id="category_id">
                                        <option selected="selected" value>Select</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ ucfirst($category->category_name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 description-container">
                                    <label for="description">Item Description</label><br/>
                                    <input type="text" name="description" class="form-control" id="description"
                                           value="{{ old('description') }}">
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
                                <div class="col-md-1">
                                    <label for="unit">&nbsp;</label><br/>
                                    <button class="add_form_field btn btn-info"><span><i class="fas fa-plus-circle" aria-hidden="false"></i></span></button>
                                </div>
                            </div>
                            <div class="additional-products">

                            </div>
                            <br/>
                            <div class="row">
                                <label for="quotation_file">Upload Quotation PDF</label><br/>
                                <div class="input-group mt-3">
                                    <input name="quotation_file" type="file"
                                           class="form-control-file"
                                           required="required">
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

            let vendor_container = $('.vendor_container'),
                description_container = $('.description_container'),
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
