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
                        <li class="breadcrumb-item">Item</li>
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
                    <form class="form-horizontal" action="{{ route('item.store.team') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="item_name">Item Name</label><br/>
                                    <input type="text" name="item_name" class="form-control" id="item_name"
                                           value="{{ old('item_name') }}">
                                    <div class="text-danger">@error('item_name'){{ $message }}@enderror</div>
                                </div>

                                <div class="col-md-3">
                                    <label for="category_id">Select Category</label><br/>
                                    <select name="category_id" class="form-control" id="category_id">
                                        <option selected="selected" value>Select</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ ucfirst($category->category_name) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger">@error('category_id'){{ $message }}@enderror</div>
                                </div>


                                <div class="col-md-3">
                                    <label for="item_description">Item Description</label><br/>
                                    <input type="text" name="item_description" class="form-control" id="item_description"
                                            value="{{ old('item_description') }}">
                                    <div class="text-danger">@error('item_description'){{ $message }}@enderror</div>
                                </div>

                                <div class="col-md-3">
                                    <label for="brand_id">Select Brand</label><br/>
                                    <select name="brand_id" class="form-control" id="brand_id">
                                        <option selected="selected" value>Select</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ ucfirst($brand->brand_name) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger">@error('brand_id'){{ $message }}@enderror</div>
                                </div>

                            </div>
                            <br/>
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <label for="price">Price</label><br/>
                                    <input type="text" name="price" class="form-control" id="price"
                                           value="{{ old('price') }}">
                                </div>
                                <div class="col-md-2 ">
                                    <label for="weight">Weight</label><br/>
                                    <input type="text" name="weight" class="form-control" id="weight"
                                           value="{{ old('weight') }}">
                                </div>
                                <div class="col-md-2 ">
                                    <label for="unit">Unit</label><br/>
                                    <input type="text" name="unit" class="form-control" id="unit"
                                           value="{{ old('unit') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="height">Height</label><br/>
                                    <input type="text" name="height" class="form-control" id="height"
                                           value="{{ old('height') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="width">width</label><br/>
                                    <input type="text" name="width" class="form-control" id="width"
                                           value="{{ old('width') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="dimension">Dimension</label><br/>
                                    <input type="text" name="dimension" class="form-control" id="dimension"
                                           value="{{ old('dimension') }}">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="picture">Item Picture</label><br/>
                                <div class="input-group mt-3">
                                    <input name="picture" type="file"
                                           class="form-control-file" accept=".jpg,.jpeg,.png,.svg">
                                </div>
                            </div>
                            <div class="row mb-2">
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
