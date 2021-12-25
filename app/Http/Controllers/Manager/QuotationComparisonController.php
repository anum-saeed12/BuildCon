<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Quotation;
use App\Models\QuotationComparison;
use App\Models\QuotationItem;
use App\Models\QuotationItemComparison;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class QuotationComparisonController extends Controller
{
    public function customer(Request $request)
    {
        $select = [
            'quotation_comparison.id',
            'customers.customer_name',
            'quotation_comparison.project_name',
            'quotation_comparison.competitor_name',
            'quotation_comparison.date',
            'quotation_comparison.total',
            'quotation_comparison.total_comparison',
            'users.name'
        ];
        $quotations = QuotationComparison::select($select)
            ->leftJoin('quotation_comparison_item', 'quotation_comparison_item.quotation_id', '=', 'quotation_comparison.id')
            ->leftJoin('brands', 'brands.id', '=', 'quotation_comparison_item.brand_id')
            ->leftJoin('items', 'items.id', '=', 'quotation_comparison_item.item_id')
            ->leftJoin('users','users.id','=','quotation_comparison.user_id')
            ->leftJoin('customers', 'customers.id', '=', 'quotation_comparison.customer_id')
            ->groupBy('quotation_comparison.id');

        # Applying filters
        # 1. Applying sales person filter
        $request->sales_person && $quotations = $quotations->where('users.id', $request->sales_person);
        # 2. Applying customer name filter
        $request->customer_id && $quotations = $quotations->where('customers.id', $request->customer_id);
        # 3. Applying project name filter
        $request->project && $quotations = $quotations->where('quotation_comparison.project_name', 'LIKE', "%$request->project%");
        # 4. Applying start date and end date filter
        $start_date = $request->from;
        $end_date = $request->to;
        $request->from && $quotations = $quotations->where('quotation_comparison.created_at', '>', $start_date);
        $request->to && $quotations = $quotations->where('quotation_comparison.created_at', '<', $end_date);

        # We have separated the paginate function so we can apply all the filters before that
        $quotations = $quotations->paginate($this->count);

        $data = [
            'title'     => 'Comparison Quotations',
            'user'      => Auth::user(),
            'quotations' => $quotations,
            'sales_people' => User::where('user_role','sale')->get(),
            'customers' => Customer::all(),
            'request' => $request,
            'reset_url' => route('comparison.list.admin')
        ];
        return view('admin.quotationcomparison.customer',$data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'currency'       =>'required',
            'customer_id'    => 'required',
            'project_name'   => 'required',
            'competitor_name'=> 'required',
            'date'           => 'required',
            'total'          => 'required',
            'total_comparison' => 'required',
            'discount'       => 'sometimes',
            'discount_comparison' => 'sometimes',
            'item_id'        => 'required|array',
            'item_id.*'      => 'required',
            'brand_id'       => 'required|array',
            'brand_id.*'     => 'required',
            'quantity'       => 'required|array',
            'quantity.*'     => 'required',
            'unit'           => 'required|array',
            'unit.*'         => 'required',
            'rate'           => 'required|array',
            'rate.*'         => 'required',
            'discount_rate'  => 'required|array',
            'discount_rate.*'=> 'required',
            'amount'         => 'required|array',
            'amount.*'       => 'required',
            'rate_comparison'           => 'required|array',
            'rate_comparison.*'         => 'required',
            'discount_rate_comparison'  => 'required|array',
            'discount_rate_comparison.*'=> 'required',
            'amount_comparison'         => 'required|array',
            'amount_comparison.*'       => 'required'
        ],[
            'customer_id.required'     => 'The customer field is required.',
            'project_name.required'    => 'The project name field is required.',
            'terms_condition.required' => 'The terms and condition field is required.'
        ]);

        $items = $request->item_id;
        $brands = $request->brand_id;
        $quantities = $request->quantity;
        $units = $request->unit;
        $rates = $request->rate;
        $discount_rates = $request->discount_rate;
        $amounts = $request->amount;
        $comparison_rates = $request->rate_comparison;
        $comparison_amounts = $request->amount_comparison;
        $comparison_discount_rates = $request->discount_rate_comparison;

        $data = $request->all();
        $id=Auth::id();
        $data['user_id']  = $id;
        $data['date'] = Carbon::parse($request->date)->format('Y-m-d');
        $data['quotation'] = Uuid::uuid4()->getHex();
        $quotation = new QuotationComparison($data);
        $quotation->save();

        $save = [];

        foreach($items as $index => $item) {
            $quotation_item = [
                'quotation_id' => $quotation->id,
                'item_id'  => $item,
                'brand_id' => $brands[$index],
                'quantity' => $quantities[$index],
                'unit'     => $units[$index],
                'rate'     => $rates[$index],
                'discount_rate' => $discount_rates[$index],
                'amount'   => $amounts[$index],
                'rate_comparison'     => $comparison_rates[$index],
                'discount_rate_comparison' => $comparison_discount_rates[$index],
                'amount_comparison'   => $comparison_amounts[$index]
            ];
            $save[] = (new QuotationItemComparison($quotation_item))->save();
        }

        return redirect(
            route('comparison.list.admin')
        )->with('success', 'QuotationComparison was added successfully!');
    }

    public function add($id)
    {
        $customers = Customer::orderBy('id','DESC')->get();
        $brands    = Brand::orderBy('id','DESC')->get();
        $items     = Item::select([
            DB::raw("DISTINCT item_name,id"),
        ])->orderBy('id','DESC')->get();

        $select = [
            "quotations.*",
           # "quotation_item.*",
            "customers.customer_name",
        ];
        $quotation = Quotation::select($select)
            ->join('customers','customers.id','=','quotations.customer_id')
            #->join('quotation_item','quotation_item.quotation_id','=','quotations.id')
            ->where('quotations.id', $id)
            ->first();

        # If quotation was not found
        if (!$quotation) return redirect()->back()->with('error', 'Quotation not found');

        $select = [
            "quotation_item.*",
            "items.item_name",
            "brands.brand_name",
            #"categories.category_name",
        ];

        $quotation->items = QuotationItem::select($select)
            ->join('items', 'items.id', '=', 'quotation_item.item_id')
            ->join('brands', 'brands.id', '=', 'quotation_item.brand_id')
            #->join('categories', 'items.id', '=', 'quotation_item.item_id')
            ->where('quotation_id', $id)
            ->get();

        $data = [
            'title'     => 'Create Comparison',
            'base_url'  => env('APP_URL', 'http://omnibiz.local'),
            'user'      => Auth::user(),
            'quotation' => $quotation,
            'brands'    => $brands,
            'customers' => $customers,
            'items'     => $items
        ];

        return view('admin.quotationcomparison.add', $data);
    }

    public function edit($id)
    {
        $customers = Customer::orderBy('id','DESC')->get();
        $items     = Item::select([
            DB::raw("DISTINCT item_name,id"),
        ])->orderBy('id','DESC')->get();

        $select = [
            "quotation_comparison.*",
            # "quotation_item.*",
            'quotation_comparison.id as quotation_id',
            "customers.*"
        ];
        $quotation = QuotationComparison::select($select)
            ->join('customers','customers.id','=','quotation_comparison.customer_id')
            #->join('quotation_item','quotation_item.quotation_id','=','quotations.id')
            ->where('quotation_comparison.id', $id)
            ->first();

        # If quotation was not found
        if (!$quotation) return redirect()->back()->with('error', 'Quotation not found');

        $select = [
            "quotation_comparison_item.*",
            "items.item_name",
            "categories.category_name",
            "categories.id as category_id",
            "brands.brand_name",
            "brands.id as brand_id",
        ];

        $quotation->items = QuotationItemComparison::select($select)
            ->join('items', 'items.id', '=', 'quotation_comparison_item.item_id')
            ->join('brands', 'brands.id', '=', 'quotation_comparison_item.brand_id')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->where('quotation_comparison_item.quotation_id', $id)
            ->get();

        $data = [
            'title'     => 'Update Comparison',
            'base_url'  => env('APP_URL', 'http://omnibiz.local'),
            'user'      => Auth::user(),
            'quotation' => $quotation,
            'customers' => $customers,
            'items'     => $items
        ];

        return view('admin.quotationcomparison.edit', $data);
    }

    public function update(Request $request,$id)
    {

        $quotation = QuotationComparison::find($id);
        $request->validate([
            'currency'       =>'required',
            'customer_id'    => 'required',
            'project_name'   => 'required',
            'competitor_name'=> 'required',
            'date'           => 'required',
            'total'          => 'required',
            'total_comparison' => 'required',
            'discount'       => 'sometimes',
            'discount_comparison' => 'sometimes',
            'item_id'        => 'required|array',
            'item_id.*'      => 'required',
            'brand_id'       => 'required|array',
            'brand_id.*'     => 'required',
            'quantity'       => 'required|array',
            'quantity.*'     => 'required',
            'unit'           => 'required|array',
            'unit.*'         => 'required',
            'rate'           => 'required|array',
            'rate.*'         => 'required',
            'discount_rate'  => 'required|array',
            'discount_rate.*'=> 'required',
            'amount'         => 'required|array',
            'amount.*'       => 'required',
            'rate_comparison'           => 'required|array',
            'rate_comparison.*'         => 'required',
            'discount_rate_comparison'  => 'required|array',
            'discount_rate_comparison.*'=> 'required',
            'amount_comparison'         => 'required|array',
            'amount_comparison.*'       => 'required'
        ],[
            'customer_id.required'     => 'The customer field is required.',
            'project_name.required'    => 'The project name field is required.',
            'competitor_name.required'    => 'The competitor name field is required.',
        ]);

        $quotation->customer_id = $request->customer_id;
        $quotation->competitor_name = $request->competitor_name;
        $quotation->project_name = $request->project_name;
        $quotation->date = $request->date;
        $quotation->currency = $request->currency;
        $quotation->discount = $request->discount;
        $quotation->discount_comparison = $request->discount_comparison;
        $quotation->total = $request->total;
        $quotation->total_comparison = $request->total_comparison;
        $quotation->save();

        $quotation_item = QuotationItemComparison::where('quotation_id',$quotation->id)->delete();

        $items = $request->item_id;
        $brands = $request->brand_id;
        $quantities = $request->quantity;
        $units = $request->unit;
        $rates = $request->rate;
        $discount_rates = $request->discount_rate;
        $amounts = $request->amount;
        $rates_comparison = $request->rate_comparison;
        $discount_rates_comparison = $request->discount_rate_comparison;
        $amounts_comparison = $request->amount_comparison;

        $save = [];

        foreach($items as $index => $item) {
            $quotation_item = [
                'quotation_id' => $quotation->id,
                'item_id'  => $item,
                'brand_id' => $brands[$index],
                'quantity' => $quantities[$index],
                'unit'     => $units[$index],
                'rate'     => $rates[$index],
                'discount_rate'=> $discount_rates[$index],
                'amount'   => $amounts[$index],
                'rate_comparison'     => $rates_comparison[$index],
                'discount_rate_comparison'=> $discount_rates_comparison[$index],
                'amount_comparison'   => $amounts_comparison[$index]
            ];
            $save[] = (new QuotationItemComparison($quotation_item))->save();
        }

        return redirect(
            route('comparison.list.admin')
        )->with('success', 'Quotation was updated successfully!');
    }

    public function delete($id)
    {
        $items = QuotationItemComparison::where('quotation_id',$id)->delete();
        $quotation = QuotationComparison::find($id)->delete();
        return redirect(
            route('comparison.list.admin')
        )->with('success', 'Customer Quotation Comparison deleted successfully!');
    }

    public function view ($id)
    {
        $select=[
            'quotations.*',
            'quotations.created_at as creationdate',
            'quotations.id as unique',
            'items.*',
            'brands.*',
            'customers.*',
            'quotation_item.*',
        ];
        $quotation = Quotation::select($select)
            ->where('quotations.id',$id)
            ->leftJoin('quotation_item', 'quotation_item.quotation_id', '=', 'quotations.id')
            ->leftJoin('brands', 'brands.id', '=', 'quotation_item.brand_id')
            ->leftJoin('items', 'items.id', '=', 'quotation_item.item_id')
            ->leftJoin('customers', 'customers.id', '=', 'quotations.customer_id')
            ->get();
        $data = [
            'title'      => 'Quotations',
            'base_url'   => env('APP_URL', 'http://omnibiz.local'),
            'user'       => Auth::user(),
            'quotation'  => $quotation
        ];
        return view('admin.quotationcomparison.item', $data);
    }


}
