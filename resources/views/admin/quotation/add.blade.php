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
                    <form class="form-horizontal" action="{{ route('quotation.store.admin') }}" method="POST">
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
                                    <input type="date" name="date" class="form-control" id="date"
                                           value="{{ old('date') ?? date('Y-m-d') }}">
                                    <div class="text-danger">@error('date'){{ $message }}@enderror</div>
                                </div>
                                <div class="col-md-2 ">
                                    <label for="currency">Currency</label><br/>
                                    <input type="text" name="currency" class="form-control" id="currency">
                                </div>
                            </div>
                            <br/>
                            <div class="row">
                                <div class="col-md-3 item-container">
                                    <label for="item_id">Select Item </label><br/>
                                    <select name="item_id[]" class="form-control trigger" id="item_id" data-target="#brand_id" data-href="{{ route('item.fetch.ajax.admin') }}" data-spinner="#item_spinner" onchange="itemSelect($(this))">
                                        <option selected="selected" value>Select</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->item_name }}">{{ ucfirst($item->item_name) }}</option>
                                        @endforeach
                                    </select>
                                    <div id="item_spinner"></div>
                                </div>
                                <div class="col-md-3 item-container">
                                    <label for="brand_id">Select Brand</label><br/>
                                    <select name="brand_id[]" class="form-control" id="brand_id"
                                            data-unit="#unit" data-rate="#rate" data-item="#item_id"
                                            data-href="{{ route('item.fetch.ajax.admin') }}"
                                            data-spinner="#brand_spinner"
                                            onchange="fetchPrice($(this))">
                                        <option selected="selected" value>Select</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ ucfirst($brand->brand_name) }}</option>
                                        @endforeach
                                    </select>
                                    <div id="brand_spinner"></div>
                                </div>
                                <div class="col-md-1 quantity-container">
                                    <label for="quantity">Quantity</label><br/>
                                    <input type="text" name="quantity[]" class="form-control with_out" id="quantity" data-target="#total_amount" data-into="#rate" onkeydown="calculate($(this))" onkeypress="calculate($(this))" onkeyup="calculate($(this))" onchange="calculate($(this))">
                                </div>
                                <div class="col-md-1 unit-container">
                                    <label for="unit">Unit</label><br/>
                                    <input type="text" name="unit[]" class="form-control" id="unit">
                                </div>
                                <div class="col-md-1 rate-container">
                                    <label for="rate">Rate</label><br/>
                                    <input type="text" name="rate[]" class="form-control with_out" id="rate" data-target="#total_amount" data-into="#quantity" onkeydown="calculate($(this))" onkeypress="calculate($(this))" onkeyup="calculate($(this))" onchange="calculate($(this))">
                                </div>
                                <div class="col-md-2 amount-container">
                                    <label for="amount">Sub-Total</label><br/>
                                    <input type="text" name="amount[]" class="form-control total n" id="amount">
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
                                <div class="col-md-2 ">
                                    <label for="total">Total Amount</label><br/>
                                    <input type="text" name="total" class="form-control" id="total"
                                           value="{{ old('total') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="terms_condition">Terms & Conditions</label><br/>
                                    <textarea class="form-control" name="terms_condition" id="terms_condition"></textarea>
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
                max_fields = 1000,
                wrapper = $('.additional-products'),
                $uid = $('.quantity').length;

            // data-target="#brand_id" data-href="{{ route('item.fetch.ajax.admin') }}"



            var x = 1;
            $(add_button).click(function(e) {
                e.preventDefault();
                $uid = $('.quantity').length;
                if (x >= max_fields) {
                    alert('You Reached the limits');
                    return false;
                }

                let $categorySelector = //'<div class="row hello">' +
                    '<div class="col-md-3 mt-3">' +
                        '<label for="item_id">Select Item</label><br/>' +
                        '<div class="row">' +
                            '<div class="col-10">' +
                                '<select name="item_id[]" class="form-control" id="item_id">' +
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
                    //;// +
                    //'</div>';

                let $itemRow = '<div class="row mt-3">' +
                    '<div class="col-md-3 item-container">' +
                    `<label for="item_id_${$uid}">Select Item </label><br/>` +
                    `<select name="item_id[]" class="form-control" id="item_id_${$uid}" data-target="#brand_id_${$uid}" data-href="{{ route('item.fetch.ajax.admin') }}" data-spinner="#item_spinner_${$uid}" onchange="itemSelect($(this))">` +
                        '<option selected="selected" value>Select</option>' +
                        @foreach ($items as $item)
                            '<option value="{{ $item->item_name }}">{{ ucfirst($item->item_name) }}</option>' +
                        @endforeach
                    '</select>' +
                    `<span id="item_spinner_${$uid}"></span>` +
                    '</div>' +
                '<div class="col-md-3 brand-container">' +
                    `<label for="brand_id_${$uid}">Select Brand</label><br/>` +
                    `<select name="brand_id[]" class="form-control" id="brand_id_${$uid}"
                        data-unit="#unit_${$uid}" data-rate="#rate_${$uid}"
                        data-item="#item_id_${$uid}"
                        data-href="{{ route('item.fetch.ajax.admin') }}"
                        data-spinner="#brand_spinner_${$uid}"
                        onchange="fetchPrice($(this))">` +
                        '<option selected="selected" value>Select</option>' +
                        @foreach ($brands as $brand)
                            '<option value="{{ $brand->id }}">{{ ucfirst($brand->brand_name) }}</option>' +
                        @endforeach
                    '</select>' +
                    `<span id="brand_spinner_${$uid}"></span>` +
                '</div>' +
                '<div class="col-md-1 quantity-container">' +
                    `<label for="quantity_${$uid}">Quantity</label><br/>` +
                    `<input type="text" name="quantity[]" class="form-control common quantity" id="quantity_${$uid}" data-target="#total_amount_${$uid}" data-into="#rate_${$uid}" onkeydown="calculate($(this))" onkeypress="calculate($(this))" onkeyup="calculate($(this))" onchange="calculate($(this))">`+
                '</div>' +
                '<div class="col-md-1 unit-container">' +
                    `<label for="unit_${$uid}">Unit</label><br/>` +
                    `<input type="text" name="unit[]" class="form-control" id="unit_${$uid}" >` +
                '</div>' +
                '<div class="col-md-1 rate-container">' +
                    `<label for="rate_${$uid}">Rate</label><br/>` +
                    `<input type="text" name="rate[]" class="form-control common" id="rate_${$uid}" data-target="#total_amount_${$uid}" data-into="#quantity_${$uid}" onkeydown="calculate($(this))" onkeypress="calculate($(this))" onkeyup="calculate($(this))" onchange="calculate($(this))">` +
                '</div>' +
                '<div class="col-md-2 amount-container">' +
                    `<label for="amount_${$uid}">Sub-Total</label><br/>` +
                    `<input type="text" name="amount[]" class="form-control total n" id="total_amount_${$uid}">` +
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
                e.preventDefault()
                $(this).parent().parent().remove();
                x--;
            })
            $('.with_out').keyup(function() {
                var txtFirstNumberValue = document.getElementById('quantity').value;
                var txtSecondNumberValue = document.getElementById('rate').value;
                var result = parseInt(txtFirstNumberValue) * parseInt(txtSecondNumberValue);
                if (!isNaN(result)) {
                    document.getElementById('amount').value = result;
                }
            })

            function sumIt() {
                var total = 0, val;
                $('.common').each(function() {
                    val = $(this).val()
                    val = isNaN(val) || $.trim(val) === "" ? 0 : parseFloat(val);
                    total += val;
                });
                $('#total_amount').val(Math.round(total));
            }

            $(document).on('keyup', '.common', sumIt,total);
                sumIt() // run when loading
        });
        function calculate(ele) {
            let total = 0,sum = 0, result, target=$(ele.data('target')),
                first = ele.val(), second = $(ele.data('into')).val(),
                sub_total, sum_of_sub_total = 0, sumOfTotal = $('#total');
                result = parseFloat(first) * parseFloat(second);
            if (!isNaN(result)) {
                $(target).val(Math.round(result));
                // Lets loop through all the total inputs
                sub_total = $('.total.n');
                for(i=0;i<sub_total.length;i++) {
                    sum_of_sub_total += parseFloat(sub_total[i].value);
                }
                sumOfTotal.val(sum_of_sub_total);
                return false;
            }
            $(target).val(0);
            sumOfTotal.val(0);
            //$('#total').val(sum_of_sub_total);
        }
        function applyDiscount() {
            let sub_total = $('.total'), discount = parseFloat($('#discount').val()) || 0, $total = 0;
            for(i=0;i<sub_total.length;i++) {
                $total += parseFloat($(`.total:eq(${i})`).val());
            }
            $total = $total - discount;
            $('#total').val($total);
        }
        $('#discount').on('keyup keydown keypress change blur focus', function(){
            applyDiscount();
        });
        function fetchItemInfo(ele) {

        }
    </script>
@stop
@include('includes.selectajax')
