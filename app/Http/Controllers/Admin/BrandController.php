<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{
    public function index()
    {
        $brand = Brand::orderBy('id','DESC')->whereNull('brands.deleted_at')->paginate($this->count);
        $data = [
            'title'   => 'Brands',
            'user'    => Auth::user(),
            'brands'  => $brand
        ];
        return view('admin.brand.view',$data);
    }

    public function add()
    {
        $data = [
            'title'    => 'Add Brand',
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'     => Auth::user(),
        ];
        return view('admin.brand.add', $data);
    }

    public function edit($id)
    {
        $brand = Brand::find($id);
        $data = [
            'title'    => 'Update Brand',
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'     => Auth::user(),
            'brand'    => $brand
        ];
        return view('admin.brand.edit', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand_name'        => 'required',
            'attention_person'   => 'required',
            'country'           => 'required'
        ], [
                'brand_name.required'      => 'The brand name field is required.',
                'attention_person.required' => 'The attention person name field is required.'
            ]
        );

        $exist = Brand::where('brand_name',$request->brand_name)->first();

        if($exist)
        {
            return redirect(
                route('brand.list.admin')
            )->with('success', 'Brand already exists!!');
        }

        $data                =  $request->all();
        $brand               = new Brand($data);
        $brand->save();

        return redirect(
            route('brand.list.admin')
        )->with('success', 'Brand was added successfully!');
    }

    public function update (Request $request,$id)
    {
        $brand = Brand::find($id);

        $request->validate([
            'brand_name'         => "required|unique:App\Models\Brand,brand_name,{$id}",
            'attention_person'   =>  'sometimes|required',
            'country'            =>  'sometimes|required'
        ],[
            'brand_name.required'      => 'The brand name field is required.',
            'attention_person.required' => 'The attended person name field is required.'
        ]);

        $request->input('brand_name')       &&  $brand->brand_name        = $request->input('brand_name');
        $request->input('attention_person') &&  $brand->attention_person  = $request->input('attention_person');
        $request->input('country')          &&  $brand->country           = $request->input('country');
        $brand->save();

        return redirect(
            route('brand.list.admin')
        )->with('success', 'Brand updated successfully!');
    }

    public function delete($id)
    {
        $brand= Brand::find($id)->delete();
        return redirect(
            route('brand.list.admin')
        )->with('success', 'Brand deleted successfully!');
    }
}
