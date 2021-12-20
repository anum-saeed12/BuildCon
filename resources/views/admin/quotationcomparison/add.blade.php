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
                        <form class="form-horizontal" action="{{ route('comparison.store.admin',$quotation->id) }}" method="POST">
                            @csrf
                            <div class="card-body pb-0">
                                <div class="row">
                                    <div class="col">
                                        <div><span>Customer: </span><b>{{ ucfirst($quotation->customer_name) }}</b></div>
                                        <div><span>Project: </span><b>{{ $quotation->project_name }}</b></div>
                                        <div><span>Date: </span><b>{{ $quotation->date }}</b></div>
                                        <div><span>Currency: </span><b>{{ $quotation->currency }}</b></div>
                                    </div>
                                </div>
                                <br/>
                                <div class="row mt-3 text-center border-bottom">
                                    <div class="col-8 pb-2 pt-2 bg-secondary border-right">
                                        Quotation Info
                                    </div>
                                    <div class="col-4 pb-2 pt-2 bg-cyan">
                                        Price Comparison
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-8 border-right bg-secondary bold text-bold">
                                        <div class="row">
                                            <div class="col-4 pb-2 pt-2">
                                                <div class="row">
                                                    <div class="col">Item</div>
                                                    <div class="col">Category</div>
                                                </div>
                                            </div>
                                            <div class="col-2 pb-2 pt-2">Quantity</div>
                                            <div class="col-2 pb-2 pt-2">Unit</div>
                                            <div class="col-2 pb-2 pt-2">Rate</div>
                                            <div class="col-2 pb-2 pt-2">Sub-Total</div>
                                        </div>
                                    </div>
                                    <div class="col-4 pb-2 pt-2 text-center text-bold bg-cyan">
                                        <div class="row">
                                            <div class="col">Rate</div>
                                            <div class="col">Discounted</div>
                                            <div class="col">Sub-Total</div>
                                        </div>
                                    </div>
                                </div>
                                @foreach($quotation->items as $quotation_item)
                                    <div class="row mt-0 border-top">
                                        <div class="col-8 border-right bg-secondary pt-2 pb-3">
                                            <div class="row">
                                                <div class="col-4 item-container">
                                                    <div class="row">
                                                        <div class="col">{{ ucfirst($quotation_item->item_name) }}</div>
                                                        <div class="col">{{ ucfirst($quotation_item->brand_name) }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-2 quantity-container">
                                                    <input type="text" name="quantity[]" value="{{ $quotation_item->quantity }}" class="form-control form-control-sm common quantity" id="quantity_{{ $loop->iteration }}" data-target="#total_amount_{{ $loop->iteration }}" data-into="#rate_{{ $loop->iteration }}" onkeydown="calculate($(this))" onkeypress="calculate($(this))" onkeyup="calculate($(this))" onchange="calculate($(this))">
                                                </div>
                                                <div class="col-2 unit-container">
                                                    <input type="text" name="unit[]" value="{{ $quotation_item->unit }}" readonly disabled class="form-control form-control-sm" id="unit_{{ $loop->iteration }}" >
                                                </div>
                                                <div class="col-2 rate-container">
                                                    <input type="text" name="rate[]" value="{{ $quotation_item->rate }}" class="form-control form-control-sm common" id="rate_{{ $loop->iteration }}" data-target="#total_amount_{{ $loop->iteration }}" data-into="#quantity_{{ $loop->iteration }}" onkeydown="calculate($(this))" onkeypress="calculate($(this))" onkeyup="calculate($(this))" onchange="calculate($(this))">
                                                </div>
                                                <div class="col-2 amount-container">
                                                    <input type="text" name="amount[]" value="{!! floatval($quotation_item->rate) * intval($quotation_item->quantity) !!}" class="form-control form-control-sm total n" id="total_amount_{{ $loop->iteration }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4 pt-2 pb-3 bg-cyan">
                                            <div class="row">
                                                <div class="col rate-container">
                                                    <input type="text" name="cp_rate[]" value="" class="form-control form-control-sm common" id="cp_rate_{{ $loop->iteration }}"{{-- data-target="#total_amount_{{ $loop->iteration }}" data-into="#quantity_{{ $loop->iteration }}" onkeydown="calculate($(this))" onkeypress="calculate($(this))" onkeyup="calculate($(this))" onchange="calculate($(this))"--}}>
                                                </div>
                                                <div class="col dc-rate-container">
                                                    <input type="text" name="cp_rate[]" value="" class="form-control form-control-sm common" id="cp_rate_{{ $loop->iteration }}"{{-- data-target="#total_amount_{{ $loop->iteration }}" data-into="#quantity_{{ $loop->iteration }}" onkeydown="calculate($(this))" onkeypress="calculate($(this))" onkeyup="calculate($(this))" onchange="calculate($(this))"--}}>
                                                </div>
                                                <div class="col amount-container">
                                                    <input type="text" name="cp_amount[]" value="" class="form-control form-control-sm total n" id="cp_total_amount_{{ $loop->iteration }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="row">
                                    <div class="col-8 border-right bg-secondary bold text-bold">
                                        <div class="row">
                                            <div class="col-10 pb-2 pt-2 text-right">
                                                <div class="pt-1">Quotation Total:</div>
                                            </div>
                                            <div class="col-2 pb-2 pt-2">
                                                <input type="text" name="discount" class="form-control form-control-sm" id="total" value="{{ $quotation->amount }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4 pb-2 pt-2 text-center text-bold bg-cyan">
                                        <div class="row">
                                            <div class="col-8 text-right">
                                                <div class="pt-1">Comparison Total:</div>
                                            </div>
                                            <div class="col">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" name="cp_discount" class="form-control form-control-sm" id="cp_discount" value="0" aria-describedby="basic-addon2">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon2">%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-8 border-right bg-secondary bold text-bold">
                                        <div class="row">
                                            <div class="col-10 pb-2 pt-2 text-right">
                                                <div class="pt-1">Applied Discount:</div>
                                            </div>
                                            <div class="col-2 pb-2 pt-2">
                                                <input type="text" name="discount" class="form-control form-control-sm" id="discount" value="{{ $quotation->discount }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4 pb-2 pt-2 text-center text-bold bg-cyan">
                                        <div class="row">
                                            <div class="col-8 text-right">
                                                <div class="pt-1">Comparison Discount:</div>
                                            </div>
                                            <div class="col">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" name="cp_discount" class="form-control form-control-sm" id="cp_discount" value="0" aria-describedby="basic-addon2">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon2">%</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
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
                    `<select name="brand_id[]" class="form-control trigger" id="brand_id_${$uid}">` +
                    '<option selected="selected" value>Select</option>' +
                    @foreach ($brands as $brand)
                        '<option value="{{ $brand->id }}">{{ ucfirst($brand->brand_name) }}</option>' +
                    @endforeach
                        '</select>' +
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
    </script>
@stop
@include('includes.selectajax')