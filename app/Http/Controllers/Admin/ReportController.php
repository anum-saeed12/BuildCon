<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Inquiry;
use App\Models\Item;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\VendorQuotationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    public function vendorQuotes(Request $request)
    {
        $item_id = $request->input('item_id');
        $items = Item::all();
        $select = [
            'items.item_name',
            'categories.category_name',
            'brands.brand_name',
            'vendor_quotation.project_name',
            'vendor_quotation.created_at',
            'vendor_quotation.id as quotation_id',
            'vendor_quotation.vendor_quotation',
            'vendor_quotation_item.rate',
            'vendor_quotation_item.amount',
            'vendors.vendor_name',
            'users.name as username',
            'users.user_role',
        ];
        $datavendor = VendorQuotationItem::select($select)
            ->leftJoin('items', 'items.id', '=', 'vendor_quotation_item.item_id')
            ->leftJoin('categories', 'categories.id', '=', 'vendor_quotation_item.category_id')
            ->leftJoin('brands', 'brands.id', '=', 'vendor_quotation_item.brand_id')
            ->leftJoin('vendor_quotation', 'vendor_quotation.id', '=', 'vendor_quotation_item.vendor_quotation_id')
            ->leftJoin('users', 'users.id', '=', 'vendor_quotation.user_id')
            ->leftJoin('vendors', 'vendors.id', '=', 'vendor_quotation.vendor_id')
            ->where('vendor_quotation_item.item_id',$item_id)
            ->orderBy('vendor_quotation.created_at','DESC')
            ->paginate($this->count);

        $data = [
            'title' =>'Vendor Quotations',
            'items' =>$items,
            'data' => $datavendor,
            'currency' => 'PKR'
        ];
        return view('admin.report.vendorQuote',$data);
    }

    public function itemWise(Request $request)
    {
        $cat_id = $request->input('category_id');
        $categorys = Category::all();
        $select = [
            'items.item_name',
            'categories.category_name',
            'items.price',
            'items.unit',
            'brands.brand_name',
        ];
        $dataItem = Item::select($select)
            ->leftJoin('brands', 'brands.id', '=', 'items.brand_id')
            ->leftJoin('categories', 'categories.id', '=', 'items.category_id')
            ->where('items.category_id',$cat_id)
            ->paginate($this->count);

        $data = [
            'title' =>'Reports',
            'categorys' =>$categorys,
            'data' => $dataItem
        ];
        return view('admin.report.itemWise',$data);
    }

    public function quotationWise(Request $request)
    {
        $item_id = $request->input('item_id');
        $items = Item::all();
        $select = [
            'items.item_name',
            'categories.category_name',
            'brands.brand_name',
            'vendor_quotation.project_name',
            'vendor_quotation_item.rate',
            'vendor_quotation_item.amount',
            'vendors.vendor_name',
        ];
        $datavendor = VendorQuotationItem::select($select)
            ->leftJoin('items', 'items.id', '=', 'vendor_quotation_item.item_id')
            ->leftJoin('categories', 'categories.id', '=', 'vendor_quotation_item.category_id')
            ->leftJoin('brands', 'brands.id', '=', 'vendor_quotation_item.brand_id')
            ->leftJoin('vendor_quotation', 'vendor_quotation.id', '=', 'vendor_quotation_item.vendor_quotation_id')
            ->leftJoin('vendors', 'vendors.id', '=', 'vendor_quotation.vendor_id')
            ->where('vendor_quotation_item.item_id',$item_id)
            ->get();

        $data = [
            'title' =>'Reports',
            'items' =>$items,
            'data' => $datavendor
        ];
        return view('admin.report.quotationWise',$data);
    }

    public function inquiryDate(Request $request)
    {
        $item_id = $request->input('item_id');
        $items = Item::all();
        $select = [
            'items.item_name',
            'categories.category_name',
            'brands.brand_name',
            'vendor_quotation.project_name',
            'vendor_quotation_item.rate',
            'vendor_quotation_item.amount',
            'vendors.vendor_name',
        ];
        $datavendor = VendorQuotationItem::select($select)
            ->leftJoin('items', 'items.id', '=', 'vendor_quotation_item.item_id')
            ->leftJoin('categories', 'categories.id', '=', 'vendor_quotation_item.category_id')
            ->leftJoin('brands', 'brands.id', '=', 'vendor_quotation_item.brand_id')
            ->leftJoin('vendor_quotation', 'vendor_quotation.id', '=', 'vendor_quotation_item.vendor_quotation_id')
            ->leftJoin('vendors', 'vendors.id', '=', 'vendor_quotation.vendor_id')
            ->where('vendor_quotation_item.item_id',$item_id)
            ->get();

        $data = [
            'title' =>'Reports',
            'items' =>$items,
            'data' => $datavendor
        ];
        return view('admin.report.inquiryDate',$data);
    }

    public function inquirySalePerson(Request $request)
    {
        $sale_id = $request->input('sale_id');
        $users = User::where('user_role','sale')->get();
        $select = [
            'users.name as username',
            'users.user_role',
            'customers.customer_name',
            'inquiries.project_name',
            'inquiries.inquiry',
            'inquiries.id as inquiry_id',
            'inquiries.created_at',
            'inquiries.date',
            'inquiries.timeline',
            DB::raw("COUNT(inquiry_order.item_id) as total_items")
        ];
        $dataSale = Inquiry::select($select)
            ->join('inquiry_order', 'inquiry_order.inquiry_id','=','inquiries.id')
            ->join('items', 'items.id','=','inquiry_order.item_id')
            ->leftJoin('customers','customers.id','=','inquiries.customer_id')
            ->leftJoin('users','users.id','=','inquiries.user_id')
            ->where('users.id',$sale_id)
            ->groupBy('inquiries.id')
            ->paginate($this->count);

        $data = [
            'title'      => 'Sales Person Inquiries',
            'salePeople' => $users,
            'data'       => $dataSale
        ];
        return view('admin.report.inquirySale',$data);
    }

    public function vendorQuotesPdf($id)
    {
        $select = [
            'items.item_name',
            'categories.category_name',
            'brands.brand_name',
            'vendor_quotation.project_name',
            'vendor_quotation_item.rate',
            'vendor_quotation_item.amount',
            'vendors.vendor_name',
        ];
        $datavendor = VendorQuotationItem::select($select)
            ->leftJoin('items', 'items.id', '=', 'vendor_quotation_item.item_id')
            ->leftJoin('categories', 'categories.id', '=', 'vendor_quotation_item.category_id')
            ->leftJoin('brands', 'brands.id', '=', 'vendor_quotation_item.brand_id')
            ->leftJoin('vendor_quotation', 'vendor_quotation.id', '=', 'vendor_quotation_item.vendor_quotation_id')
            ->leftJoin('vendors', 'vendors.id', '=', 'vendor_quotation.vendor_id')
            ->where('vendor_quotation_item.item_id',$id)
            ->get();

        $data = [
            'title' =>'Reports',
            'data' => $datavendor
        ];
        $date = "Vendor-Report-". Carbon::now()->format('d-M-Y')  .".pdf";
        $pdf = PDF::loadView('admin.report.vendorQuote-pdf', $data);
        return $pdf->download($date);
    }

}
