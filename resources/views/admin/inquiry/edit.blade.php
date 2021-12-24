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
                        <li class="breadcrumb-item">Edit Inquiry</li>
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
                    <form class="form-horizontal" action="{{ route('inquiry.update.admin',$inquiry->id) }}" method="POST">
                        @csrf
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="customer_id">Select Customer</label><br/>
                                    <select name="customer_id" class="form-control form-control-sm" id="customer_id">
                                        <option selected="selected" value>Select Customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"{{ $inquiry->customer_name==$customer->customer_name ? ' selected':'' }}>{{ ucfirst($customer->customer_name) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger">@error('customer_id'){{ $message }}@enderror</div>
                                </div>
                                <div class="col">
                                    <label for="project_name">Project Name</label><br/>
                                    <input type="text" name="project_name" class="form-control form-control-sm" id="project_name"
                                            value="{{ $inquiry->project_name }}">
                                    <div class="text-danger">@error('project_name'){{ $message }}@enderror</div>
                                </div>

                                <div class="col">
                                    <label for="date">Date</label><br/>
                                    <input type="date" name="date" class="form-control form-control-sm" id="date"
                                           value="{{ $inquiry->date }}">
                                    <div class="text-danger">@error('date'){{ $message }}@enderror</div>
                                </div>

                                <div class="col">
                                    <label for="timeline">Timeline</label><br/>
                                    <input type="date" name="timeline" class="form-control form-control-sm" id="timeline"
                                           value="{{ $inquiry->timeline }}">
                                    <div class="text-danger">@error('timeline'){{ $message }}@enderror</div>
                                </div>
                            </div>
                            <br/>
                            <div class="row">
                                <div class="col-md-3 category-container">
                                    <label for="category_id">Select Category</label><br/>
                                    <select name="category_id[]" class="form-control form-control-sm categories" id="category_id" data-target="#item_id" data-href="{{ route('category.fetch.ajax.admin') }}" data-spinner="#category_spinner" onchange="categorySelect($(this))">
                                        <option selected="selected" value>Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"{{ $inquiry->items[0]->category_id==$category->id ? ' selected':'' }}>{{ ucfirst($category->category_name) }}</option>
                                        @endforeach
                                    </select>
                                    <div id="category_spinner"></div>
                                    <div class="text-danger">@error('category_id'){{ $message }}@enderror</div>
                                </div>
                                <div class="col-md-3 item-container">
                                    <label for="item_id">Select Item </label><br/>
                                    <select name="item_id[]" class="form-control form-control-sm trigger" id="item_id" data-target="#brand_id" data-href="{{ route('item.fetch.ajax.admin') }}" data-spinner="#item_spinner" onchange="itemSelect($(this))">
                                        <option selected="selected" value>Select Item</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->item_name }}"{{ $inquiry->items[0]->item_name==$item->item_name ? ' selected':'' }}>{{ ucfirst($item->item_name) }}</option>
                                        @endforeach
                                    </select>
                                    <div id="item_spinner"></div>
                                </div>
                                <div class="col-md-2 brand-container">
                                    <label for="brand_id">Select Brand</label><br/>
                                    <select name="brand_id[]" class="form-control form-control-sm" id="brand_id">
                                        <option selected="selected" value>Select Brand</option>
                                        @foreach (fetchBrandsForItem($inquiry->items[0]->item_name) as $brand)
                                            <option value="{{ $brand->id }}"{{ $inquiry->items[0]->brand_id==$brand->id ?' selected':'' }}>{{ ucfirst($brand->brand_name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col quantity-container">
                                    <label for="quantity">Quantity</label><br/>
                                    <input type="text" name="quantity[]" class="form-control form-control-sm with_out" id="quantity" data-target="#total_amount" data-into="#rate" onkeydown="calculate($(this))" onkeypress="calculate($(this))" onkeyup="calculate($(this))" onchange="calculate($(this))" value="{{ $inquiry->items[0]->quantity }}">
                                </div>
                                <div class="col unit-container">
                                    <label for="unit">Unit</label><br/>
                                    <input type="text" name="unit[]" class="form-control form-control-sm" id="unit" value="{{ $inquiry->items[0]->unit }}">
                                </div>
                                <div class="col-0">
                                    <label for="button">&nbsp;</label><br/>
                                    <button type="button" class="add_form_field btn btn-info btn-sm"><span><i class="fas fa-plus-circle" aria-hidden="false"></i></span></button>
                                </div>
                            </div>
                            <div class="additional-products">
                                @foreach($inquiry->items as $inquiry_item)
                                    @php if (isset($loop) && $loop->iteration <= 1) continue; @endphp
                                    <div class="row mt-3">
                                        <div class="col-md-3 category-container">
                                            <select name="category_id[]" class="form-control form-control-sm" id="category_id_{{ $loop->iteration }}" data-target="#item_id_{{ $loop->iteration }}" data-href="{{ route('category.fetch.ajax.admin') }}" data-spinner="#item_spinner_{{ $loop->iteration }}" onchange="categorySelect($(this))">
                                                <option selected="selected" value>Select Category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"{{ $category->id == $inquiry_item->category_id ? ' selected':'' }}>{{ ucfirst($category->category_name) }}</option>
                                                @endforeach
                                            </select>
                                            <div id="category_spinner"></div>
                                            <div class="text-danger">@error('category_id'){{ $message }}@enderror</div>
                                        </div>
                                        <div class="col-md-3 item-container">
                                            <select name="item_id[]" class="form-control form-control-sm" id="item_id_{{ $loop->iteration }}" data-target="#brand_id_{{ $loop->iteration }}" data-href="{{ route('item.fetch.ajax.admin') }}" data-spinner="#item_spinner_{{ $loop->iteration }}" onchange="itemSelect($(this))">
                                                <option selected="selected" value>Select Item</option>
                                                @foreach ($items as $item)
                                                    <option value="{{ $item->item_name }}"{{ $inquiry_item->item_name == $item->item_name ? " selected":'' }}>{{ ucfirst($item->item_name) }}</option>
                                                @endforeach
                                            </select>
                                            <span id="item_spinner_${$uid}"></span>
                                        </div>
                                        <div class="col-md-2 brand-container">
                                            <select name="brand_id[]" class="form-control form-control-sm" id="brand_id_{{ $loop->iteration }}">
                                                <option selected="selected" value>Select Brand</option>
                                                @foreach (fetchBrandsForItem($inquiry_item->item_name) as $brand)
                                                    <option value="{{ $brand->id }}"{{ $brand->id == $inquiry_item->brand_id ? ' selected':'' }}>{{ ucfirst($brand->brand_name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col quantity-container">
                                            <input type="text" name="quantity[]" value="{{ $inquiry_item->quantity }}" class="form-control form-control-sm common quantity" id="quantity_{{ $loop->iteration }}" data-target="#total_amount_{{ $loop->iteration }}" data-into="#rate_{{ $loop->iteration }}" onkeydown="calculate($(this))" onkeypress="calculate($(this))" onkeyup="calculate($(this))" onchange="calculate($(this))">
                                        </div>
                                        <div class="col unit-container">
                                            <input type="text" name="unit[]" value="{{ $inquiry_item->unit }}" class="form-control form-control-sm" id="unit_{{ $loop->iteration }}" >
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
                                    <label for="remarks">Remarks</label><br/>
                                    <textarea class="form-control form-control-sm" name="remarks" id="remarks">{{ $inquiry->remarks }}</textarea>
                                </div>
                            </div>
                            <br/>
                            <div class="row">
                                <div class="col mb-3 text-center">
                                    <button type="reset" class="btn btn-default">Cancel</button>
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
                amount_container = $('.amount-container'),
                rate_container = $('.rate-container'),
                unit_container = $('.unit-container'),
                $uid = $('.quantity').length,
                add_button = $(".add_form_field"),
                wrapper = $('.additional-products');

            var x = 1;
            $(add_button).click(function(e) {
                e.preventDefault();
                $uid = $('.category').length;

                let $itemRow = '<div class="row mt-3 ">' +
                    '<div class="col-md-3 category-container">' +
                    `<select name="category_id[]" class="form-control form-control-sm categories" id="category_id_${$uid}" data-target="#item_id_${$uid}" data-href="{{ route('category.fetch.ajax.admin') }}" data-spinner="#category_spinner_${$uid}" onchange="categorySelect($(this))">` +
                    '<option selected="selected" value>Select Category</option>' +
                    @foreach ($categories as $category)
                        '<option value="{{ $category->id }}">{{ ucfirst($category->category_name) }}</option>'+
                    @endforeach
                        '</select>' +
                    `<div id="category_spinner_${$uid}"></div>` +
                    '</div>' +
                    '<div class="col-md-3 item-container">' +
                    `<select name="item_id[]" class="form-control form-control-sm " id="item_id_${$uid}" data-target="#brand_id_${$uid}" data-href="{{ route('item.fetch.ajax.admin') }}" data-spinner="#item_spinner_${$uid}" onchange="itemSelect($(this))">` +
                    '<option selected="selected" value>Select Item</option>' +
                    @foreach ($items as $item)
                        '<option value="{{ $item->item_name }}">{{ ucfirst($item->item_name) }}</option>'+
                    @endforeach
                        '</select>' +
                    `<div id="item_spinner_${$uid}"></div>` +
                    '</div>' +
                    '<div class="col-md-2 brand-container">' +
                    `<select name="brand_id[]" class="form-control form-control-sm" id="brand_id_${$uid}">` +
                    '<option selected="selected" value>Select Brand</option>' +
                    @foreach ($brands as $brand)
                        '<option value="{{ $brand->id }}">{{ ucfirst($brand->brand_name) }}</option>'+
                    @endforeach
                        '</select>' +
                    '</div>' +
                    '<div class="col quantity-container">' +
                    `<input type="text" name="quantity[]" class="form-control form-control-sm common quantity" id="quantity_${$uid}" data-target="#total_amount_${$uid}" data-into="#rate_${$uid}" onkeydown="calculate($(this))" onkeypress="calculate($(this))" onkeyup="calculate($(this))" onchange="calculate($(this))">`+
                    '</div>' +
                    '<div class="col unit-container">' +
                    `<input type="text" name="unit[]" class="form-control form-control-sm" id="unit_${$uid}" >` +
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
