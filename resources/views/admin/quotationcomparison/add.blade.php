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
                                            <div class="col pb-2 pt-2">Quantity</div>
                                            <div class="col pb-2 pt-2">Unit</div>
                                            <div class="col pb-2 pt-2">Rate</div>
                                            <div class="col pb-2 pt-2">{{ ceil($quotation->discount) }}% Off</div>
                                            <div class="col pb-2 pt-2">Sub-Total</div>
                                        </div>
                                    </div>
                                    <div class="col-4 pb-2 pt-2 text-center text-bold bg-cyan">
                                        <div class="row">
                                            <div class="col">Rate</div>
                                            <div class="col">Discounted</div>
                                            <div class="col">Sub-Total</div>
                                            <div class="col">Change</div>
                                        </div>
                                    </div>
                                </div>
                                @foreach($quotation->items as $quotation_item)
                                    <div class="row mt-0 mb-0 border-top">
                                        <div class="col-8 border-right bg-secondary pt-2 pb-2">
                                            <div class="row">
                                                <div class="col-4 item-container">
                                                    <div class="row">
                                                        <div class="col">{{ ucfirst($quotation_item->item_name) }}</div>
                                                        <div class="col">{{ ucfirst($quotation_item->brand_name) }}</div>
                                                    </div>
                                                </div>
                                                <div class="col quantity-container">
                                                    <input type="text" name="quantity[]" value="{{ $quotation_item->quantity }}"
                                                           class="form-control form-control-sm common quantity"
                                                           id="quantity_{{ $loop->iteration }}"
                                                           {{--data-target="#total_amount_{{ $loop->iteration }}"
                                                           data-into="#rate_{{ $loop->iteration }}"
                                                           onkeydown="calculate($(this))" onkeypress="calculate($(this))"
                                                           onkeyup="calculate($(this))" onchange="calculate($(this))"--}}
                                                           readonly>
                                                </div>
                                                <div class="col unit-container">
                                                    <input type="text" name="unit[]" value="{{ $quotation_item->unit }}"
                                                           class="form-control form-control-sm" id="unit_{{ $loop->iteration }}"
                                                           readonly disabled>
                                                </div>
                                                <div class="col rate-container">
                                                    <input type="text" name="rate[]" value="{{ $quotation_item->rate }}"
                                                           class="form-control form-control-sm common" id="rate_{{ $loop->iteration }}"
                                                           {{--data-target="#total_amount_{{ $loop->iteration }}"
                                                           data-into="#quantity_{{ $loop->iteration }}"
                                                           onkeydown="calculate($(this))" onkeypress="calculate($(this))"
                                                           onkeyup="calculate($(this))" onchange="calculate($(this))"--}}
                                                           readonly>
                                                </div>
                                                <div class="col discount_rate-container">
                                                    <input type="text" name="discount_rate[]" value="{{ $quotation_item->discount_rate }}"
                                                           class="form-control form-control-sm with_out discounted_rate"
                                                           id="discount_rate_{{ $loop->iteration }}" data-id="{{ $loop->iteration }}"
                                                           {{--data-target="#total_amount_{{ $loop->iteration }}"
                                                           data-into="#quantity_{{ $loop->iteration }}"
                                                           onkeydown="calculate($(this))" onkeypress="calculate($(this))"
                                                           onkeyup="calculate($(this))" onchange="calculate($(this))"--}}
                                                           readonly>
                                                </div>
                                                <div class="col amount-container">
                                                    <input type="text" name="amount[]"
                                                           value="{!! floatval($quotation_item->discount_rate) * intval($quotation_item->quantity) !!}"
                                                           class="form-control form-control-sm total n"
                                                           id="total_amount_{{ $loop->iteration }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4 pt-2 pb-2 bg-cyan">
                                            <div class="row">
                                                <div class="col rate-container">
                                                    <input type="number" name="cp_rate[]"
                                                           class="form-control form-control-sm cp-rate"
                                                           id="cp_rate_{{ $loop->iteration }}"
                                                           data-target="#cp_total_{{ $loop->iteration }}"
                                                           data-into="#quantity_{{ $loop->iteration }}"
                                                           onkeydown="calculate($(this))" onkeypress="calculate($(this))"
                                                           onkeyup="calculate($(this))" onchange="calculate($(this))"/>
                                                </div>
                                                <div class="col discount_rate-container">
                                                    <input type="text" name="cp_discount_rate[]"
                                                           class="form-control form-control-sm with_out cp_discounted_rate"
                                                           id="cp_discount_rate_{{ $loop->iteration }}" data-id="{{ $loop->iteration }}"
                                                           data-target="#cp_total_amount_{{ $loop->iteration }}"
                                                           data-into="#quantity_{{ $loop->iteration }}"
                                                           onkeydown="calculate($(this))" onkeypress="calculate($(this))"
                                                           onkeyup="calculate($(this))" onchange="calculate($(this))"
                                                           readonly/>
                                                </div>
                                                <div class="col amount-container">
                                                    <input type="text" name="cp_amount[]"
                                                           class="form-control form-control-sm cp_total n"
                                                           id="cp_total_amount_{{ $loop->iteration }}"/>
                                                </div>
                                                <div class="col change-container">
                                                    <input type="text" name="cp_change[]"
                                                           class="form-control form-control-sm cp-change"
                                                           id="cp_change_{{ $loop->iteration }}"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="row">
                                    <div class="col-8 border-right bg-secondary bold text-bold">
                                        <div class="row">
                                            <div class="col-10 pb-2 pt-2 text-right">
                                                <div class="pt-1">Applied Discount:</div>
                                            </div>
                                            <div class="col-2 pb-2 pt-2">
                                                <div class="input-group input-group-sm">
                                                    <input type="number" name="discount" class="form-control form-control-sm"
                                                           id="discount" value="{{ $quotation->discount }}"/>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
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
                                                    <input type="number" name="cp_discount"
                                                           class="form-control form-control-sm"
                                                           id="cp_discount" value="0"/>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">%</span>
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
                                                <div class="pt-1">Quotation Total:</div>
                                            </div>
                                            <div class="col-2 pb-2 pt-2">
                                                <input type="number" name="total" class="form-control form-control-sm"
                                                       id="total" value="{{ $quotation->total }}"
                                                       readonly/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4 pb-2 pt-2 text-center text-bold bg-cyan">
                                        <div class="row">
                                            <div class="col-8 text-right">
                                                <div class="pt-1">Comparison Total:</div>
                                            </div>
                                            <div class="col">
                                                <input type="text" name="cp_total" class="form-control form-control-sm"
                                                       id="cp_total" value="0" readonly/>
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
            //$(document).on('keyup', '.common', sumIt);
            //sumIt() // run when loading
        });
        function calculate() {
            let quotation_discount = $('#discount'),
                cp_discount = $('#cp_discount'),
                quotation_total, cp_total,
                items = $('.cp_discounted_rate');
            items.each(function(){
                let id = $(this).data('id'),
                    target = $($(this).data('target')),
                    quotation_rate = parseFloat($(`#discount_rate_${id}`).val()),
                    quotation_quantity = $(`#quantity_${id}`).val(),
                    cp_rate_input = $(`#cp_rate_${id}`),
                    cp_discount_rate_input = $(`#cp_discount_rate_${id}`),
                    cp_sub_total_input = $(`#cp_total_amount_${id}`),
                    cp_change_input = $(`#cp_change_${id}`),
                    cp_rate = parseFloat(cp_rate_input.val()),
                    applied_discount = parseFloat($('#cp_discount').val()),
                    cp_discounted_price, sub_total, cp_sub_total_o, cp_sub_total, change;
                cp_rate = cp_rate>0?cp_rate:0;
                applied_discount = applied_discount>0?(100-applied_discount)/100:1;
                applied_discount = Math.ceil(applied_discount);
                cp_discounted_price = cp_rate * applied_discount;
                // Update the discounted price
                cp_discount_rate_input.val(cp_discounted_price);
                // Sub total Qty. x Price
                cp_sub_total_o = quotation_quantity * cp_discounted_price;
                // Sub total (discount applied)
                cp_sub_total = cp_sub_total_o * applied_discount;
                cp_sub_total_input.val(cp_sub_total);
                // Calculate change
                change = (quotation_rate - cp_discounted_price / quotation_rate);
                cp_change_input.val(change);
                let str = `CP: ${cp_rate} | DC: ${applied_discount} | NR: ${cp_discounted_price}`;
                console.log(str);
                //let cp_sub_total = parseInt(quantity) * rate()
            });
        }
    </script>
@stop
