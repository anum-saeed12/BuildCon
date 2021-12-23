<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        $customer = Customer::orderBy('id','DESC')->whereNull('customers.deleted_at')->paginate($this->count);
        $data = [
            'title'    => 'View Customers',
            'user'     => Auth::user(),
            'customer' => $customer,
        ];
        return view('admin.customer.view',$data);
    }

    public function add()
    {
        $data = [
            'title'    => 'Add Customer',
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'     => Auth::user(),
        ];
        return view('admin.customer.add', $data);
    }

    public function edit($id)
    {
        $customer = Customer::find($id);
        $data = [
            'title'    => 'Update Customer',
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'     => Auth::user(),
            'customer' => $customer
        ];
        return view('admin.customer.edit', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'     => 'required',
            'attention_person'  => 'required',
            'address'           => 'required'
        ], [
                'customer_name.required'      => 'The customer name field is required.',
                'attention_person.required'   => 'The attention person name field is required.'
            ]
        );

        $exist = Customer::where('customer_name',$request->customer_name)
                         ->where('attention_person',$request->attention_person)
                         ->where('address',$request->address)
                         ->first();
        if($exist)
        {
            return redirect(
                route('customer.list.admin')
            )->with('success', 'Customer already exists!!');
        }

        $data                =  $request->all();
        $customer            =  new Customer($data);
        $customer->save();

        return redirect(
            route('customer.list.admin')
        )->with('success', 'Customer was added successfully!');
    }

    public function update (Request $request,$id)
    {
        $customer = Customer::find($id);

        $request->validate([
            'customer_name'     =>  'sometimes',
            'attention_person'  =>  'sometimes',
            'address'           =>  'sometimes'
        ],[
            'customer_name.required'      => 'The customer name field is required.',
            'attention_person.required'   => 'The attention person name field is required.'
        ]);
        $exist = Customer::where('customer_name',$request->customer_name)
            ->where('address',$request->address)
            ->where('attention_person',$request->attention_person)
            ->first();

        if($exist)
        {
            return redirect(
                route('customer.list.admin')
            )->with('success', 'Customer already exists!!');
        }

        $request->input('customer_name')    &&  $customer->customer_name     = $request->input('customer_name');
        $request->input('attention_person') &&  $customer->attention_person  = $request->input('attention_person');
        $request->input('address')          &&  $customer->address           = $request->input('address');
        $customer->save();

        return redirect(
            route('customer.list.admin')
        )->with('success', 'Customer updated successfully!');
    }

    public function delete($id)
    {
        $customer = Customer::find($id)->delete();
        return redirect(
            route('customer.list.admin')
        )->with('success', 'Customer deleted successfully!');
    }
}
