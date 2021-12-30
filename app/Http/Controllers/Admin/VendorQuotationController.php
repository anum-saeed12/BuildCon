<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Item;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Vendor;
use App\Models\VendorQuotation;
use App\Models\VendorQuotationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class VendorQuotationController extends Controller
{
    public function index()
    {
        $select = [
            'vendor_quotation.quotation_pdf',
            'vendor_quotation.id',
            'vendor_quotation.date',
            'vendor_quotation.quotation_ref',
            'vendors.vendor_name',
            'vendor_quotation.project_name',
            'vendor_quotation.total',
            'users.name'];

        $vendors_quotation = VendorQuotation::select($select)
            ->leftJoin('vendors', 'vendor_quotation.vendor_id', '=', 'vendors.id')
            ->leftJoin('users', 'users.id', '=', 'vendor_quotation.user_id')
            ->leftJoin('vendor_quotation_item', 'vendor_quotation_item.vendor_quotation_id', '=', 'vendor_quotation.id')
            ->leftJoin('categories', 'categories.id', '=', 'vendor_quotation_item.category_id')
            ->groupBy('vendor_quotation.id')
            ->paginate($this->count);
        $data = [
            'title'            => 'Vendor Quotation',
            'vendor_quotation' => $vendors_quotation,
            'user'             => Auth::user()
        ];
        return view('admin.vendorquotation.view',$data);
    }

    public function add()
    {
        $vendors = Vendor::orderBy('id','DESC')->get();
        $categories = Category::orderBy('id','DESC')->get();
        $items = Item::orderBy('id','DESC')->get();
        $brands = Brand::orderBy('id','DESC')->get();
        $data = [
            'title'       => 'Add Vendor Quotation',
            'base_url'    => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'        => Auth::user(),
            'vendors'     => $vendors,
            'categories'  => $categories,
            'brands'      => $brands,
            'items'       => $items
        ];
        return view('admin.vendorquotation.add', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendor_id'          => 'required',
            'quotation_ref'      => 'required',
            'quotation_pdf'      => 'required|file',
            'project_name'       => 'required',
            'date'               => 'required',
            'currency'           => 'required',
            'total'              => 'required',
            'category_id'        => 'required|array',
            'category_id.*'      => 'required',
            'item_id'            => 'required|array',
            'item_id.*'          => 'required',
            'brand_id'           => 'required|array',
            'brand_id.*'         => 'required',
            'quantity'           => 'required|array',
            'quantity.*'         => 'required',
            'unit'               => 'required|array',
            'unit.*'             => 'required',
            'rate'               => 'required|array',
            'rate.*'             => 'required',
            'amount'             => 'required|array',
            'amount.*'           => 'required'
        ],[
            'vendor_id.required'     => 'The vendor field is required.',
        ]);

        $categories   = $request->category_id;
        $quantities   = $request->quantity;
        $units        = $request->unit;
        $rates        = $request->rate;
        $amounts      = $request->amount;
        $brands       = $request->brand_id;
        $items        = $request->item_id;

        $id = Auth::user()->id;
        $data = $request->all();
        $data['user_id']  = $id;
        $data['vendor_quotation'] = Uuid::uuid4()->getHex();
        $vendor_quotation = new VendorQuotation($data);
        $vendor_quotation['quotation_pdf']     =  $this->uploadPDF($request->file('quotation_pdf'));
        $vendor_quotation->save();

        $save = [];

        foreach($categories as $index => $category) {
            $item_detail = Item::select('*')
                ->where('item_name',$items[$index])
                ->where('brand_id', $brands[$index])
                ->first();
            $vendor_quotation_item = [
                'vendor_quotation_id' => $vendor_quotation->id,
                'category_id'         => $category,
                'brand_id'            => $brands[$index],
                'item_id'             => $item_detail->id,
                'quantity'            => $quantities[$index],
                'unit'                => $units[$index],
                'rate'                => $rates[$index],
                'amount'              => $amounts[$index],
            ];
            $save[] = (new VendorQuotationItem($vendor_quotation_item))->save();

        }
        return redirect(
            route('vendorquotation.list.admin')
        )->with('success', 'Vendor Quotation was added successfully!');
    }

    private function uploadPDF($file)
    {
        $filename  = Uuid::uuid4().".{$file->extension()}";
        $private_path = $file->storeAs('public/file',$filename);
        $public_path  = Storage::url("file/$filename");
        return $filename;
    }

    public function delete($id)
    {
        $items = VendorQuotationItem::where('vendor_quotation_id',$id)->delete();
        $quotation = VendorQuotation::find($id)->delete();
        return redirect(
            route('vendorquotation.list.admin')
        )->with('success', 'Vendor Quotation deleted successfully!');
    }

    public function edit($id)
    {
        $vendors = Vendor::orderBy('id','DESC')->get();
        $brands    = Brand::orderBy('id','DESC')->get();
        $categories  = Category::orderBy('id','DESC')->get();
        $items     = Item::select([
            DB::raw("DISTINCT item_name,id"),
        ])->orderBy('id','DESC')->get();

        $select = [
            "vendor_quotation.*",
            "vendor_quotation.id as ids",
            "vendor_quotation.total as totals",
            #"vendor_quotation_item.*",
            'vendors.*'
        ];

        $vendor_quotation = VendorQuotation::select($select)
            ->join('vendors','vendors.id','=','vendor_quotation.vendor_id')
            #->join('vendor_quotation_item','vendor_quotation_item.vendor_quotation_id','=','vendor_quotation.id')
            ->where('vendor_quotation.id', $id)
            ->first();


        # If inquiry was not found
        if (!$vendor_quotation) return redirect()->back()->with('error', 'Vendor Quotation not found');

        $select_items = [
            "vendor_quotation_item.*",
            "items.item_name"
        ];

        $vendor_quotation->items = VendorQuotationItem::select($select_items)
            ->leftJoin('items', 'items.id', '=', 'vendor_quotation_item.item_id')
            ->where('vendor_quotation_item.vendor_quotation_id', $id)
            ->get();


        $data = [
            'title'            => 'Edit Vendor Quotation',
            'base_url'         => env('APP_URL', 'http://omnibiz.local'),
            'user'             => Auth::user(),
            'vendor_quotation' => $vendor_quotation,
            'brands'           => $brands,
            'vendors'          => $vendors,
            'items'            => $items,
            'categories'       =>$categories
        ];

        return view('admin.vendorquotation.edit', $data);
    }

    public function update(Request $request,$id)
    {
        $vendor_quotation = VendorQuotation::find($id);

        if(!$vendor_quotation)
        {
            return redirect(
                route('vendorquotation.list.admin')
            )->with('error', 'Vendor Quotation doesn\'t exists!');
        }

        $request->validate([
            'vendor_id'          => 'required',
            'quotation_ref'      => 'required',
            'project_name'       => 'required',
            'date'               => 'required',
            'currency'           => 'required',
            'total'              => 'required',
            'category_id'        => 'required|array',
            'category_id.*'      => 'required',
            'item_id'            => 'required|array',
            'item_id.*'          => 'required',
            'brand_id'           => 'required|array',
            'brand_id.*'         => 'required',
            'quantity'           => 'required|array',
            'quantity.*'         => 'required',
            'unit'               => 'required|array',
            'unit.*'             => 'required',
            'rate'               => 'required|array',
            'rate.*'             => 'required',
            'amount'             => 'required|array',
            'amount.*'           => 'required'
        ],[
            'vendor_id.required'     => 'The vendor field is required.',
        ]);

        $vendor_quotation->vendor_id     = $request->vendor_id;
        $vendor_quotation->project_name  = $request->project_name;
        $vendor_quotation->quotation_ref = $request->quotation_ref;
        $vendor_quotation->total         = $request->total;
        $vendor_quotation->save();

        $vendor_quotation_item = VendorQuotationItem::where('vendor_quotation_id',$vendor_quotation->id)->delete();

        $items      = $request->item_id;
        $categories = $request->category_id;
        $brands     = $request->brand_id;
        $quantities = $request->quantity;
        $units      = $request->unit;
        $rates      = $request->rate;
        $amounts    = $request->amount;

        $save = [];


        foreach($categories as $index => $category)
        {
            $item_detail = Item::where('item_name',$items[$index])->where('brand_id', $brands[$index])->first();

            $vendor_quotation_item = [
                'vendor_quotation_id' => $vendor_quotation->id,
                'category_id'         => $category,
                'item_id'             => $item_detail->id,
                'brand_id'            => $brands[$index],
                'quantity'            => $quantities[$index],
                'unit'                => $units[$index],
                'rate'                => $rates[$index],
                'amount'              => $amounts[$index],
            ];
            $save[] = (new VendorQuotationItem($vendor_quotation_item))->save();
        }
        return redirect(
            route('vendorquotation.list.admin')
        )->with('success', 'Vendor Quotation was updated successfully!');
    }

    public function view($id)
    {
        $select=[
            'vendor_quotation.vendor_quotation',
            'vendor_quotation.project_name',
            'vendor_quotation.quotation_ref',
            'vendor_quotation.created_at as creationdate',
            'vendor_quotation.id as unique',
            'vendor_quotation.total as totals',
            'vendor_quotation.created_at',
            'vendor_quotation.currency',
            'items.item_name',
            'items.item_description',
            'vendors.vendor_name',
            'vendors.attended_person',
            'categories.category_name',
            'brands.brand_name',
            'vendor_quotation_item.unit',
            'vendor_quotation_item.quantity',
            'vendor_quotation_item.amount',
            'vendor_quotation.total',
            'vendor_quotation_item.rate',
        ];
        $vendors_quotation = VendorQuotation::select($select)
            ->where('vendor_quotation.id',$id)
            ->leftJoin('vendor_quotation_item', 'vendor_quotation_item.vendor_quotation_id', '=', 'vendor_quotation.id')
            ->leftJoin('vendors', 'vendor_quotation.vendor_id', '=', 'vendors.id')
            ->leftJoin('items', 'items.id', '=', 'vendor_quotation_item.item_id')
            ->leftJoin('users', 'users.id', '=', 'vendor_quotation.user_id')
            ->leftJoin('brands', 'brands.id', '=', 'vendor_quotation_item.brand_id')
            ->leftJoin('categories', 'categories.id', '=', 'vendor_quotation_item.category_id')
            ->get();
        $data = [
            'title'            => 'Vendor Quotation',
            'user'             => Auth::user(),
            'quotation'        => $vendors_quotation
        ];
        return view('admin.vendorquotation.item',$data);
    }

    public function pdfinquiry($id)
    {
        $select=[
            'items.item_name',
            'items.item_description',
            'items.category_id',
            'brands.brand_name',
            'categories.category_name',
            'vendor_quotation_item.quantity',
            'vendor_quotation_item.amount',
            'vendor_quotation_item.unit',
            'vendor_quotation_item.rate',
            'categories.category_name',
        ];

        $quotation_select = [
            'vendor_quotation.vendor_quotation',
            'vendor_quotation.id',
            'vendor_quotation.project_name',
            'vendor_quotation.total',
            'vendor_quotation.quotation_ref',
            'vendor_quotation.currency',
            'vendor_quotation.created_at',
            'vendors.vendor_name',
            'vendors.attended_person'
        ];
        $quotation = VendorQuotation::select($quotation_select)
            ->join('vendors', 'vendors.id','=','vendor_quotation.vendor_id')
            ->where('vendor_quotation.id', $id)
            ->first();

        if (!isset($quotation->id)) return redirect()->back()->with('error', 'Vendor Quotation not found');

        $quotation->items = VendorQuotationItem::select($select)
            ->leftJoin('brands', 'brands.id', '=', 'vendor_quotation_item.brand_id')
            ->leftJoin('items', 'items.id', '=', 'vendor_quotation_item.item_id')
            ->leftJoin('categories', 'categories.id', '=', 'items.category_id')
            ->orderBy('items.category_id','ASC')
            ->where('vendor_quotation_item.vendor_quotation_id',$quotation->id)
            ->groupBy('items.id')
            ->get();
        $quotation->creation = \Illuminate\Support\Carbon::createFromTimeStamp(strtotime($quotation->created_at))->format('d-M-Y');

        $data = [
            'title'      => 'Vendor Quotation Pdf',
            'user'       => Auth::user(),
            'quotation'  => $quotation
        ];
        $date = "Vendor-Quotation-Invoice-". Carbon::now()->format('d-M-Y')  .".pdf";
        $pdf = PDF::loadView('admin.vendorquotation.pdf-invoice', $data);
        return $pdf->download($date);
    }


}
