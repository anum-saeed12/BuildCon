<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function index()
    {
        $vendor = Vendor::orderBy('id','DESC')->paginate($this->count);
        $data = [
            'title'   => 'View Vendors',
            'user'    => Auth::user(),
            'vendors' => $vendor
        ];
        return view('admin.vendor.view',$data);
    }

    public function add()
    {
        $data = [
            'title'    => 'Add Vendor',
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'     => Auth::user(),

        ];
        return view('admin.vendor.add', $data);
    }

    public function edit($id)
    {
        $vendor = Vendor::find($id);
        $data = [
            'title'    => 'Update Vendor',
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'     => Auth::user(),
            'vendor'   => $vendor
        ];
        return view('admin.vendor.edit', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendor_name'       => 'required',
            'attended_person'  => 'required',
            'address'           => 'required',
            'country'           => 'required',
        ], [
                'vendor_name.required'      => 'The vendor name field is required.',
                'attended_person.required' => 'The attention person name field is required.'
            ]
        );

        $exist = Vendor::where('vendor_name',$request->vendor_name)
            ->where('attended_person',$request->attended_person)
            ->where('country',$request->country)
            ->where('address',$request->address)
            ->first();
        if($exist)
        {
            return redirect(
                route('vendor.list.admin')
            )->with('success', 'Vendor already exists!!');
        }

        $data     =  $request->all();
        $vendor   =  new Vendor($data);
        $vendor->save();

        return redirect(
            route('vendor.list.admin')
        )->with('success', 'Vendor was added successfully!');
    }

    public function update (Request $request,$id)
    {
        $vendor = Vendor::find($id);

        $request->validate([
            'vendor_name'       => 'required',
            'attention_person'  => 'required',
            'address'           => 'required',
            'country'           => 'required',
        ], [
            'vendor_name.required'      => 'The vendor name field is required.',
            'attention_person.required' => 'The attention person name field is required.'
        ]);
        $exist = Vendor::where('vendor_name',$request->vendor_name)
            ->where('attended_person',$request->attended_person)
            ->where('address',$request->address)
            ->where('country',$request->country)
            ->first();

        if($exist)
        {
            return redirect(
                route('vendor.list.admin')
            )->with('success', 'Vendor already exists!!');
        }

        $request->input('vendor_name')      &&  $vendor->vendor_name     = $request->input('vendor_name');
        $request->input('attention_person') &&  $vendor->attention_person   = $request->input('attention_person');
        $request->input('address')          &&  $vendor->address           = $request->input('address');
        $request->input('country')          &&  $vendor->country           = $request->input('country');
        $vendor->save();

        return redirect(
            route('vendor.list.admin')
        )->with('success', 'Vendor updated successfully!');
    }

    public function delete($id)
    {
        $vendor = Vendor::find($id)->delete();
        return redirect(
            route('vendor.list.admin')
        )->with('success', 'Vendor deleted successfully!');
    }
}
