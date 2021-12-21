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
            @if($errors->any())
                {{ implode('', $errors->all('<div>:message</div>')) }}
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <form class="form-horizontal" action="{{ route('vendorquotation.update.admin',$vendor_quotation->ids) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body pb-0">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="vendor_id">Select Vendor</label><br/>
                                        <select name="vendor_id" class="form-control" id="vendor_id">
                                            <option selected="selected" value>Select</option>
                                            @foreach ($vendors as $vendor)
                                                <option value="{{ $vendor->id }}"{{ $vendor_quotation->vendor_name==$vendor->vendor_name ? ' selected':'' }}>{{ ucfirst($vendor->vendor_name) }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">@error('vendor_id'){{ $message }}@enderror</div>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="project_name">Project Name</label><br/>
                                        <input type="text" name="project_name" class="form-control" id="project_name"
                                               value="{{ $vendor_quotation->project_name }}">
                                        <div class="text-danger">@error('project_name'){{ $message }}@enderror</div>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="quotation_ref">Quotation Ref#</label><br/>
                                        <input type="text" name="quotation_ref" class="form-control" id="quotation_ref"
                                               value="{{ $vendor_quotation->quotation_ref }}">
                                        <div class="text-danger">@error('quotation_ref'){{ $message }}@enderror</div>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="date">Date</label><br/>
                                        <input type="date" name="date" class="form-control" id="date"
                                               value="{{ $vendor_quotation->date }}">
                                        <div class="text-danger">@error('date'){{ $message }}@enderror</div>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="currency">Currency</label><br/>
                                        <input type="text" name="currency" class="form-control" id="currency"
                                               value="{{ $vendor_quotation->currency }}">
                                        <div class="text-danger">@error('currency'){{ $message }}@enderror</div>
                                    </div>

                                    <div class="col-md-2">
                                        <a href="{{ route('vendor.add.admin') }}" class="btn btn-success"><i class="fa fa-plus-circle mr-1"></i> Add Vendor</a>
                                    </div>

                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col-md-3 category-container">
                                        <label for="category_id">Select Category</label><br/>
                                        <select name="category_id[]" class="form-control categories" id="category_id" data-target="#item_id" data-href="{{ route('category.fetch.ajax.admin') }}" data-spinner="#category_spinner" onchange="categorySelect($(this))">
                                            <option selected="selected" value>Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"{{ $vendor_quotation->items[0]->category_id==$category->id ? ' selected':'' }}>{{ ucfirst($category->category_name ) }}</option>
                                            @endforeach
                                        </select>
                                        <div id="category_spinner"></div>
                                        <div class="text-danger">@error('category_id'){{ $message }}@enderror</div>
                                    </div>
                                    <div class="col-md-2 item-container">
                                        <label for="item_id">Select Item</label><br/>
                                        <select name="item_id[]" class="form-control" id="item_id"  data-target="#brand_id" data-href="{{ route('item.fetch.ajax.admin') }}" data-spinner="#item_spinner" onchange="itemSelect($(this))">
                                            <option selected="selected" value>Select Item</option>
                                            @foreach ($items as $item)
                                                <option value="{{ $item->item_name }}"{{ $vendor_quotation->items[0]->item_name==$item->item_name ? ' selected':'' }}>{{ ucfirst($item->item_name ) }}</option>
                                            @endforeach
                                        </select>
                                        <div id="item_spinner"></div>
                                        <div class="text-danger">@error('item_id'){{ $message }}@enderror</div>
                                    </div>
                                    <div class="col-md-2 brand-container">
                                        <label for="brand_id">Select Brand</label><br/>
                                        <select name="brand_id[]" class="form-control" id="brand_id">
                                            <option selected="selected" value>Select Brand</option>
                                            @foreach (fetchBrandsForItem($vendor_quotation->items[0]->item_name) as $brand)
                                                <option value="{{ $brand->id }}"{{ $vendor_quotation->items[0]->brand_id==$brand->id  ? ' selected':'' }}>{{ ucfirst($brand->brand_name  ) }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">@error('brand_id'){{ $message }}@enderror</div>
                                    </div>
                                    <div class="col-md-1 quantity-container">
                                        <label for="quantity">Quantity</label><br/>
                                        <input type="text" name="quantity[]" class="form-control with_out" id="quantity" value="{{ $vendor_quotation->items[0]->quantity }}" data-target="#total_amount" data-into="#rate" onkeydown="calculate($(this))" onkeypress="calculate($(this))" onkeyup="calculate($(this))" onchange="calculate($(this))">
                                    </div>
                                    <div class="col-md-1 unit-container">
                                        <label for="unit">Unit</label><br/>
                                        <input type="text" name="unit[]" class="form-control" id="unit" value="{{ $vendor_quotation->items[0]->unit }}">
                                    </div>
                                    <div class="col-md-1 rate-container">
                                        <label for="rate">Rate</label><br/>
                                        <input type="text" name="rate[]" class="form-control with_out" id="rate" value="{{ $vendor_quotation->items[0]->rate }}" data-target="#total_amount" data-into="#quantity" onkeydown="calculate($(this))" onkeypress="calculate($(this))" onkeyup="calculate($(this))" onchange="calculate($(this))">
                                    </div>
                                    <div class="col-md-1 amount-container">
                                        <label for="amount">Sub-Total</label><br/>
                                        <input type="text" name="amount[]" class="form-control total n" value="{!! floatval($vendor_quotation->items[0]->rate) * intval($vendor_quotation->items[0]->quantity) !!}" id="amount">
                                    </div>
                                    <div class="col-md-1">
                                        <label for="button">&nbsp;</label><br/>
                                        <button class="add_form_field btn btn-info"><span><i class="fas fa-plus-circle" aria-hidden="false"></i></span></button>
                                    </div>
                                </div>
                                <div class="additional-products">
                                    @foreach($vendor_quotation->items as $vendor_quotation_item)
                                        @php if (isset($loop) && $loop->iteration <= 1) continue; @endphp
                                        <div class="row mt-3">
                                            <div class="col-md-3 category-container">
                                                <label for="category_id">Select Category</label><br/>
                                                <select name="category_id[]" class="form-control" id="category_id_{{ $loop->iteration - 1 }}" data-target="#item_id_{{ $loop->iteration - 1 }}" data-href="{{ route('category.fetch.ajax.admin') }}" data-spinner="#item_spinner_{{ $loop->iteration - 1 }}" onchange="categorySelect($(this))">
                                                    <option selected="selected" value>Select</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"{{ $category->id == $vendor_quotation_item->category_id ? ' selected':'' }}>{{ ucfirst($category->category_name) }}</option>
                                                    @endforeach
                                                </select>
                                                <div id="category_spinner"></div>
                                                <div class="text-danger">@error('category_id'){{ $message }}@enderror</div>
                                            </div>
                                            <div class="col-md-2 item-container">
                                                <label for="item_id_${$uid}">Select Item </label><br/>
                                                <select name="item_id[]" class="form-control" id="item_id_{{ $loop->iteration - 1 }}" data-target="#brand_id_{{ $loop->iteration - 1 }}" data-href="{{ route('item.fetch.ajax.admin') }}" data-spinner="#item_spinner_{{ $loop->iteration - 1 }}" onchange="itemSelect($(this))">
                                                    <option selected="selected" value>Select</option>
                                                    @foreach ($items as $item)
                                                        <option value="{{ $item->item_name }}"{{ $vendor_quotation_item->item_name == $item->item_name ? " selected":'' }}>{{ ucfirst($item->item_name) }}</option>
                                                    @endforeach
                                                </select>
                                                <span id="item_spinner_${$uid}"></span>
                                            </div>
                                            <div class="col-md-2 brand-container">
                                                <label for="brand_id_{{ $loop->iteration - 1 }}">Select Brand</label><br/>
                                                <select name="brand_id[]" class="form-control" id="brand_id_{{ $loop->iteration - 1 }}">
                                                    <option selected="selected" value>Select</option>
                                                    @foreach (fetchBrandsForItem($vendor_quotation_item->item_name) as $brand)
                                                        <option value="{{ $brand->id }}"{{ $brand->id == $vendor_quotation_item->brand_id ? ' selected':'' }}>{{ ucfirst($brand->brand_name) }}</option>
                                                    @endforeach
                                                    {{--    @foreach ($brands as $brand)
                                                            <option value="{{ $brand->brand_name }}"{{ $vendor_quotation_item->brand_name == $brand->brand_name ? " selected":'' }}>{{ ucfirst($brand->brand_name) }}</option>
                                                        @endforeach--}}
                                                </select>
                                            </div>
                                            <div class="col-md-1 quantity-container">
                                                <label for="quantity_{{ $loop->iteration - 1 }}">Quantity</label><br/>
                                                <input type="text" name="quantity[]" value="{{ $vendor_quotation_item->quantity }}" class="form-control common quantity" id="quantity_{{ $loop->iteration - 1 }}" data-target="#total_amount_{{ $loop->iteration - 1 }}" data-into="#rate_{{ $loop->iteration - 1 }}" onkeydown="calculate($(this))" onkeypress="calculate($(this))" onkeyup="calculate($(this))" onchange="calculate($(this))">
                                            </div>
                                            <div class="col-md-1 unit-container">
                                                <label for="unit_{{ $loop->iteration - 1 }}">Unit</label><br/>
                                                <input type="text" name="unit[]" value="{{ $vendor_quotation_item->unit }}" class="form-control" id="unit_{{ $loop->iteration - 1 }}" >
                                            </div>
                                            <div class="col-md-1 rate-container">
                                                <label for="rate_{{ $loop->iteration - 1 }}">Rate</label><br/>
                                                <input type="text" name="rate[]" value="{{ $vendor_quotation_item->rate }}" class="form-control common" id="rate_{{ $loop->iteration - 1 }}" data-target="#total_amount_{{ $loop->iteration - 1 }}" data-into="#quantity_{{ $loop->iteration - 1 }}" onkeydown="calculate($(this))" onkeypress="calculate($(this))" onkeyup="calculate($(this))" onchange="calculate($(this))">
                                            </div>
                                            <div class="col-md-1 amount-container">
                                                <label for="amount_{{ $loop->iteration - 1 }}">Sub-Total</label><br/>
                                                <input type="text" name="amount[]" value="{!! floatval($vendor_quotation_item->rate) * intval($vendor_quotation_item->quantity) !!}" class="form-control total n" id="total_amount_{{ $loop->iteration - 1 }}">
                                            </div>
                                            <div class="col-md-1">
                                                <label for="unit">&nbsp;</label><br/>
                                                <button type="button" class="delete btn btn-danger"><span><i class="fas fa-trash-alt" aria-hidden="false"></i></span></button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col-md-4 ">
                                        <label for="total">Total Amount</label><br/>
                                        <input type="text" name="total" class="form-control" id="total" value="{{ $vendor_quotation->totals }}">
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

            let vendor_container = $('.vendor_container'),e
            description_container = $('.description_container'),
                brand_container = $('.brand-container'),
                quantity_container = $('.quantity-container'),
                unit_container = $('.unit-container'),
                price_container = $('.price-container'),
                amount_container = $('.amount-container'),
                add_button = $(".add_form_field"),
                wrapper = $('.additional-products');
            $uid = $('.quantity').length;

            var x = 1;
            $(add_button).click(function(e) {
                e.preventDefault();
                $uid = $('.quantity').length + 1;

                let $itemRow = '<div class="row mt-3 ">' +
                    '<div class="col-md-3 category-container">' +
                        '<label for="category_id">Select Category</label><br/>' +
                        `<select name="category_id[]" class="form-control categories" id="category_id_${$uid}" data-target="#item_id_${$uid}" data-href="{{ route('category.fetch.ajax.admin') }}" data-spinner="#category_spinner_${$uid}" onchange="categorySelect($(this))">` +
                            '<option selected="selected" value>Select</option>' +
                                @foreach ($categories as $category)
                                    '<option value="{{ $category->id }}">{{ ucfirst($category->category_name) }}</option>'+
                                @endforeach
                            '</select>' +
                        `<div id="category_spinner_${$uid}"></div>` +
                    '</div>' +
                    '<div class="col-md-2 item-container">' +
                        '<label for="item_id">Select Item</label><br/>' +
                        `<select name="item_id[]" class="form-control" id="item_id_${$uid}" data-target="#brand_id_${$uid}" data-href="{{ route('item.fetch.ajax.admin') }}" data-spinner="#item_spinner_${$uid}" onchange="itemSelect($(this))">` +
                            '<option selected="selected" value>Select</option>' +
                                @foreach ($items as $item)
                                    '<option value="{{ $item->item_name }}">{{ ucfirst($item->item_name) }}</option>'+
                                @endforeach
                        '</select>' +
                        `<div id="item_spinner_${$uid}"></div>` +
                    '</div>' +
                    '<div class="col-md-2 brand-container">' +
                        '<label for="brand_id">Select Brand</label><br/>' +
                        `<select name="brand_id[]" class="form-control" id="brand_id_${$uid}">` +
                            '<option selected="selected" value>Select</option>' +
                            @foreach ($brands as $brand)
                                '<option value="{{ $brand->id }}">{{ ucfirst($brand->brand_name) }}</option>'+
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
                    '<div class="col-md-1 amount-container">' +
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
                calculateTotal();
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

            $(document).on('keyup', '.common', sumIt,total);
            sumIt() // run when loading
        });
        function sumIt() {
            var total = 0, val;
            $('.common').each(function() {
                val = $(this).val()
                val = isNaN(val) || $.trim(val) === "" ? 0 : parseFloat(val);
                total += val;
            });
            $('#total_amount').val(Math.round(total));
        }
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


