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
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.team') }}">Home</a></li>
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
                        <form class="form-horizontal" action="{{ route('quotation.update.team',$quotation->id) }}" method="POST">
                            @csrf
                            <div class="card-body pb-0">
                                <div class="row">
                                    <div class="col">
                                        <label for="customer_id">Select Customer</label><br/>
                                        <select name="customer_id" class="form-control form-control-sm" id="customer_id">
                                            <option selected="selected" value>Select</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"{{ $quotation->customer_id==$customer->id ? ' selected':'' }}>{{ ucfirst($customer->customer_name) }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">@error('customer_id'){{ $message }}@enderror</div>
                                    </div>
                                    <div class="col">
                                        <label for="project_name">Project Name</label><br/>
                                        <input type="text" name="project_name" class="form-control form-control-sm" id="project_name"
                                               value="{{ $quotation->project_name }}">
                                        <div class="text-danger">@error('project_name'){{ $message }}@enderror</div>
                                    </div>

                                    <div class="col">
                                        <label for="date">Date</label><br/>
                                        <input type="date" name="date" class="form-control form-control-sm" id="date"
                                               value="{{ $quotation->date }}">
                                        <div class="text-danger">@error('date'){{ $message }}@enderror</div>
                                    </div>
                                    <div class="col">
                                        <label for="currency">Currency</label><br/>
                                        <input type="text" name="currency" class="form-control form-control-sm" id="currency" value="{{ $quotation->currency }}">
                                    </div>
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col-md-2 category-container">
                                        <label for="category_id">Select Category</label><br/>
                                        <select name="category_id[]" class="form-control form-control-sm categories" id="category_id" data-target="#item_id" data-href="{{ route('category.fetch.ajax.team') }}" data-spinner="#category_spinner" onchange="categorySelect($(this))">
                                            <option selected="selected" value>Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"{{ $quotation->items[0]->category_id==$category->id ? ' selected':'' }}>{{ ucfirst($category->category_name) }}</option>
                                            @endforeach
                                        </select>
                                        <div id="category_spinner"></div>
                                        <div class="text-danger">@error('category_id'){{ $message }}@enderror</div>
                                    </div>
                                    <div class="col-md-2 item-container">
                                        <label for="item_id">Select Item </label><br/>
                                        <select name="item_id[]" class="form-control form-control-sm trigger" id="item_id" data-target="#brand_id" data-href="{{ route('item.fetch.ajax.team') }}" data-spinner="#item_spinner" onchange="itemSelect($(this))">
                                            <option selected="selected" value>Select Item</option>
                                            @foreach ($items as $item)
                                                <option value="{{ $item->item_name }}"{{ $quotation->items[0]->item_id==$item->id ? ' selected':'' }}>{{ ucfirst($item->item_name) }}</option>
                                            @endforeach
                                        </select>
                                        <div id="item_spinner"></div>
                                    </div>
                                    <div class="col-md-2 brand-container">
                                        <label for="brand_id">Select Brand</label><br/>
                                        <select name="brand_id[]" class="form-control form-control-sm" id="brand_id"
                                                data-unit="#unit" data-rate="#rate"
                                                data-quantity="#quantity" data-amount="#total_amount"
                                                data-item="#item_id"
                                                data-href="{{ route('item.fetch.ajax.team') }}"
                                                data-spinner="#brand_spinner"
                                                onchange="fetchPrice($(this))">
                                            <option value>Select</option>
                                            @foreach (fetchBrandsForItem($quotation->items[0]->item_name) as $brand)
                                                <option value="{{ $brand->id }}"{{ $quotation->items[0]->brand_id == $brand->id ? ' selected':'' }}>{{ ucfirst($brand->brand_name) }}</option>
                                            @endforeach
                                        </select>
                                        <span id="brand_spinner"></span>
                                    </div>
                                    <div class="col quantity-container">
                                        <label for="quantity">Quantity</label><br/>
                                        <input type="text" name="quantity[]" value="{{ $quotation->items[0]->quantity }}"
                                               class="form-control form-control-sm with_out" id="quantity"
                                               data-target="#total_amount" data-into="#rate" data-dc="#discount_rate"
                                               onkeydown="calculate($(this))" onkeypress="calculate($(this))"
                                               onkeyup="calculate($(this))" onchange="calculate($(this))">
                                    </div>
                                    <div class="col unit-container">
                                        <label for="unit">Unit</label><br/>
                                        <input type="text" name="unit[]" class="form-control form-control-sm" id="unit" value="{{ $quotation->items[0]->unit }}">
                                    </div>
                                    <div class="col rate-container">
                                        <label for="rate">Rate</label><br/>
                                        <input type="text" name="rate[]" value="{{ $quotation->items[0]->rate }}"
                                               class="form-control form-control-sm with_out" id="rate"
                                               data-target="#total_amount" data-into="#quantity" data-dc="#discount_rate"
                                               onkeydown="calculate($(this))" onkeypress="calculate($(this))"
                                               onkeyup="calculate($(this))" onchange="calculate($(this))">
                                    </div>
                                    <div class="col discount_rate-container">
                                        <label for="discount_rate"><span id="dc_txt">{{ $quotation->discount }}</span>% Off</label><br/>
                                        <input type="text" name="discount_rate[]" class="form-control form-control-sm with_out discounted_rate"
                                               id="discount_rate" data-id="none"
                                               data-target="#total_amount" data-into="#quantity"
                                               onkeydown="calculate($(this))" onkeypress="calculate($(this))"
                                               onkeyup="calculate($(this))" onchange="calculate($(this))"
                                               value="{{ $quotation->items[0]->discount_rate }}" readonly>
                                    </div>
                                    <div class="col amount-container">
                                        <label for="amount">Sub-Total</label><br/>
                                        <input type="text" name="amount[]" value="{!! floatval($quotation->items[0]->rate) * intval($quotation->items[0]->quantity) !!}" class="form-control form-control-sm total n" id="total_amount">
                                    </div>
                                    <div class="col-0">
                                        <label for="unit">&nbsp;</label><br/>
                                        <button type="button" class="add_form_field btn btn-info btn-sm"><span><i class="fas fa-plus-circle" aria-hidden="false"></i></span></button>
                                    </div>
                                </div>
                                <div class="additional-products">
                                    @foreach($quotation->items as $quotation_item)
                                        @php if (isset($loop) && $loop->iteration <= 1) continue; @endphp
                                        <div class="row mt-3">
                                            <div class="col-md-2 category-container">
                                                <select name="category_id[]" class="form-control form-control-sm" id="category_id_{{ $loop->iteration }}" data-target="#item_id_{{ $loop->iteration }}" data-href="{{ route('category.fetch.ajax.team') }}" data-spinner="#item_spinner_{{ $loop->iteration }}" onchange="categorySelect($(this))">
                                                    <option selected="selected" value>Select Category</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"{{ $category->id == $quotation_item->category_id ? ' selected':'' }}>{{ ucfirst($category->category_name) }}</option>
                                                    @endforeach
                                                </select>
                                                <div id="category_spinner"></div>
                                                <div class="text-danger">@error('category_id'){{ $message }}@enderror</div>
                                            </div>
                                            <div class="col-md-2 item-container">
                                                <select name="item_id[]" class="form-control form-control-sm trigger" id="item_id_{{ $loop->iteration - 1 }}" data-target="#brand_id_{{ $loop->iteration - 1 }}" data-href="{{ route('item.fetch.ajax.team') }}" data-spinner="#item_spinner_{{ $loop->iteration - 1 }}" onchange="itemSelect($(this))">
                                                    <option selected="selected" value>Select Item</option>
                                                    @foreach ($items as $item)
                                                        <option value="{{ $item->item_name }}"{{ $quotation_item->item_name==$item->item_name ? ' selected':'' }}>{{ ucfirst($item->item_name) }}</option>
                                                    @endforeach
                                                </select>
                                                <span id="item_spinner_{{ $loop->iteration - 1 }}"></span>
                                            </div>
                                            <div class="col-md-2 brand-container">
                                                <select name="brand_id[]" class="form-control form-control-sm" id="brand_id_{{ $loop->iteration - 1 }}"
                                                        data-unit="#unit_{{ $loop->iteration - 1 }}" data-rate="#rate_{{ $loop->iteration - 1 }}"
                                                        data-quantity="#quantity_{{ $loop->iteration - 1 }}" data-amount="#total_amount_{{ $loop->iteration - 1 }}"
                                                        data-item="#item_id_{{ $loop->iteration - 1 }}"
                                                        data-href="{{ route('item.fetch.ajax.team') }}"
                                                        data-spinner="#brand_spinner_{{ $loop->iteration - 1 }}"
                                                        onchange="fetchPrice($(this))">
                                                    <option selected="selected" value>Select Brand</option>
                                                    @foreach (fetchBrandsForItem($quotation_item->item_name) as $brand)
                                                        <option value="{{ $brand->id }}"{{ $brand->id == $quotation_item->brand_id ? ' selected':'' }}>{{ ucfirst($brand->brand_name) }}</option>
                                                    @endforeach
                                                </select>
                                                <span id="brand_spinner_{{ $loop->iteration - 1 }}"></span>
                                            </div>
                                            <div class="col quantity-container">
                                                <input type="text" name="quantity[]" value="{{ $quotation_item->quantity }}"
                                                       class="form-control form-control-sm common quantity" id="quantity_{{ $loop->iteration - 1 }}"
                                                       data-target="#total_amount_{{ $loop->iteration - 1 }}" data-into="#rate_{{ $loop->iteration - 1 }}"
                                                       data-dc="#discount_rate_{{ $loop->iteration - 1 }}"
                                                       onkeydown="calculate($(this))" onkeypress="calculate($(this))"
                                                       onkeyup="calculate($(this))" onchange="calculate($(this))">
                                            </div>
                                            <div class="col unit-container">
                                                <input type="text" name="unit[]" value="{{ $quotation_item->unit }}" class="form-control form-control-sm" id="unit_{{ $loop->iteration - 1 }}" >
                                            </div>
                                            <div class="col rate-container">
                                                <input type="text" name="rate[]" value="{{ $quotation_item->rate }}"
                                                       class="form-control form-control-sm common" id="rate_{{ $loop->iteration - 1 }}"
                                                       data-target="#total_amount_{{ $loop->iteration - 1 }}"
                                                       data-into="#quantity_{{ $loop->iteration - 1 }}"
                                                       data-dc="#discount_rate_{{ $loop->iteration - 1 }}"
                                                       onkeydown="calculate($(this))" onkeypress="calculate($(this))"
                                                       onkeyup="calculate($(this))" onchange="calculate($(this))">
                                            </div>
                                            <div class="col discount_rate-container">
                                                <input type="text" name="discount_rate[]" class="form-control form-control-sm with_out discounted_rate"
                                                       id="discount_rate_{{ $loop->iteration - 1 }}" data-id="{{ $loop->iteration - 1 }}"
                                                       data-target="#total_amount_{{ $loop->iteration - 1 }}"
                                                       data-into="#quantity_{{ $loop->iteration - 1 }}"
                                                       onkeydown="calculate($(this))" onkeypress="calculate($(this))"
                                                       onkeyup="calculate($(this))" onchange="calculate($(this))"
                                                       value="{{ $quotation_item->discount_rate }}" readonly>
                                            </div>
                                            <div class="col amount-container">
                                                <input type="text" name="amount[]" value="{!! floatval($quotation_item->rate) * intval($quotation_item->quantity) !!}" class="form-control form-control-sm total n" id="total_amount_{{ $loop->iteration - 1 }}">
                                            </div>
                                            <div class="col-0">
                                                <button type="button" class="delete btn btn-danger btn-sm"><span><i class="fas fa-trash-alt" aria-hidden="false"></i></span></button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col">
                                        <label for="discount">Discount</label><br/>
                                        <div class="input-group input-group-sm mb-3">
                                            <input type="number" min="0" max="100" step="1"
                                                   name="discount" class="form-control" id="discount"
                                                   value="{{ $quotation->discount }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label for="total">Total Amount</label><br/>
                                        <input type="text" name="total" class="form-control form-control-sm" id="total"
                                               value="0">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="terms_condition">Terms & Conditions</label><br/>
                                        <textarea class="form-control form-control-sm" name="terms_condition" id="terms_condition"> {{ $quotation->terms_condition }}</textarea>
                                    </div>
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col mb-3 text-center">
                                        <button type="button" onclick="window.location.href='{{ url()->previous() }}'" class="btn btn-default">Cancel</button>
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
                discount_rate_container = $('.discount_rate-container'),
                add_button = $(".add_form_field"),
                wrapper = $('.additional-products'),
                $uid = $('.quantity').length + 1,
                x = 1;
            $(add_button).click(function(e) {
                e.preventDefault();
                $uid = $('.quantity').length + 1;

                let $itemRow = '<div class="row mt-3">' +
                    '<div class="col-md-2 category-container">' +
                    `<select name="category_id[]" class="form-control form-control-sm categories" id="category_id_${$uid}" data-target="#item_id_${$uid}" data-href="{{ route('category.fetch.ajax.team') }}" data-spinner="#category_spinner_${$uid}" onchange="categorySelect($(this))">` +
                    '<option selected="selected" value>Select Category</option>' +
                    @foreach ($categories as $category)
                        '<option value="{{ $category->id }}">{{ ucfirst($category->category_name) }}</option>'+
                    @endforeach
                        '</select>' +
                    `<div id="category_spinner_${$uid}"></div>` +
                    '</div>' +
                    '<div class="col-md-2 item-container">' +
                    `<select name="item_id[]" class="form-control form-control-sm" id="item_id_${$uid}" data-target="#brand_id_${$uid}" data-href="{{ route('item.fetch.ajax.team') }}" data-spinner="#item_spinner_${$uid}" onchange="itemSelect($(this))">` +
                    '<option selected="selected" value>Select Item</option>' +
                        '</select>' +
                    `<span id="item_spinner_${$uid}"></span>` +
                    '</div>' +
                    '<div class="col-md-2 brand-container">' +
                    `<select name="brand_id[]" class="form-control form-control-sm" id="brand_id_${$uid}"
                            data-unit="#unit_${$uid}" data-rate="#rate_${$uid}"
                            data-quantity="#quantity_${$uid}" data-amount="#total_amount_${$uid}"
                            data-item="#item_id_${$uid}"
                            data-href="{{ route('item.fetch.ajax.team') }}"
                            data-spinner="#brand_spinner_${$uid}"
                            onchange="fetchPrice($(this))">` +
                    '<option selected="selected" value>Select Brand</option>' +
                    '</select>' +
                    `<span id="brand_spinner_${$uid}"></span>` +
                    '</div>' +
                    '<div class="col quantity-container">' +
                    `<input type="text" name="quantity[]" class="form-control form-control-sm common quantity" id="quantity_${$uid}"
                            data-target="#total_amount_${$uid}" data-into="#rate_${$uid}" data-dc="#discount_rate_${$uid}"
                            onkeydown="calculate($(this))" onkeypress="calculate($(this))"
                            onkeyup="calculate($(this))" onchange="calculate($(this))">`+
                    '</div>' +
                    '<div class="col unit-container">' +
                    `<input type="text" name="unit[]" class="form-control form-control-sm" id="unit_${$uid}" >` +
                    '</div>' +
                    '<div class="col rate-container">' +
                    `<input type="text" name="rate[]" class="form-control form-control-sm common" id="rate_${$uid}"
                            data-target="#total_amount_${$uid}" data-into="#quantity_${$uid}" data-dc="#discount_rate_${$uid}"
                            onkeydown="calculate($(this))" onkeypress="calculate($(this))"
                            onkeyup="calculate($(this))" onchange="calculate($(this))">` +
                    '</div>' +
                    '<div class="col discount_rate-container">' +`<input type="text" name="discount_rate[]" class="form-control form-control-sm discounted_rate" id="discount_rate_${$uid}" data-id="${$uid}" data-target="#total_amount_${$uid}" data-into="#quantity_${$uid}" onkeydown="calculate($(this))" onkeypress="calculate($(this))" onkeyup="calculate($(this))" onchange="calculate($(this))" readonly>` +
                    '</div>' +
                    '<div class="col amount-container">' +
                    `<input type="text" name="amount[]" class="form-control form-control-sm total n" id="total_amount_${$uid}">` +
                    '</div>' +
                    '<div class="col-0">' +
                    '<button class="delete btn btn-danger btn-sm"><span><i class="fas fa-trash-alt" aria-hidden="false"></i></span></button>' +
                    '</div>' +
                    '</div>';
                x++;
                $(wrapper).append($itemRow); // add input box
            });
            $(wrapper).on("click", ".delete", function(e) {
                e.preventDefault()
                $(this).parent().parent().remove();
                calculateTotal();
                x--;
            })
            function sumIt() {
                let total = 0, val;
                $('.common').each(function() {
                    val = $(this).val(),
                        val = isNaN(val) || $.trim(val) === "" ? 0 : parseFloat(val);
                    total += parseFloat(val).toFixed(2);
                });
                $('#total_amount').val(total);
                applyDiscount();
            }

            $(document).on('keyup', '.common', sumIt,total);
            sumIt() // run when loading
        });
        function calculate(ele) {
            let total = 0,sum = 0, result, target=$(ele.data('target')),
                first = ele.val(), second = $(ele.data('into')).val(),
                discounted_price_input = $(ele.data('dc')), discount_applied = parseFloat($('#discount').val()) / 100, discounted_price,
                sub_total, sum_of_sub_total = 0, sumOfTotal = $('#total');
            result = parseFloat(first) * parseFloat(second);
            if (!isNaN(result)) {
                $(target).val(parseFloat(result).toFixed(2));
                sub_total = $('.total.n');
                for(i=0;i<sub_total.length;i++) {
                    sum_of_sub_total += parseFloat(sub_total[i].value);
                }
                sumOfTotal.val(parseFloat(sum_of_sub_total).toFixed(2));
                applyDiscount();
                return false;
            }
            $(target).val(0);
            sumOfTotal.val(0);
            applyDiscount();
        }
        function applyDiscount() {
            let sub_total = $('.total'),
                discount = parseFloat($('#discount').val()),
                discountPercentage=discount, $total = 0;
            discount = discount>0?(100-discount)/100:1;
            discountPercentage = discountPercentage>0?discountPercentage:0;
            $('.discounted_rate').each(function(){
                let id = $(this).data('id'),
                    rate = id!=='none'?parseFloat($('#rate_' + id).val()):parseFloat($('#rate').val()),
                    discount_price_input = $(this), discounted_price, item_total, discounted_total,
                    discount_text=id!=='none'?$('#dc_txt_' + id):$('#dc_txt'),
                    sub_total=id!=='none'?$('#total_amount_' + id):$('#total_amount'),
                    quantity = id!=='none'?parseFloat($('#quantity_' + id).val()):parseFloat($('#quantity').val());

                // Validate values
                rate = rate>0?rate:0;
                quantity = quantity>0?quantity:0;
                item_total = rate * quantity;
                discounted_price = parseFloat(rate) * parseFloat(discount);
                discounted_total = parseFloat(item_total) * parseFloat(discount);
                discount_price_input.val(discounted_price.toFixed(2));
                sub_total.val(discounted_total.toFixed(2));
                discount_text.html(discountPercentage);
                $total += discounted_total;
            })
            $('#total').val($total);
        }
        $('#discount').on('keyup keydown keypress change blur focus', function(){
            applyDiscount();
        });
        function fetchItemInfo(ele) {

        }
    </script>
    @include('includes.selectajax')
@stop
