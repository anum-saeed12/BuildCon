<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Inquiry;
use App\Models\InquiryOrder;
use App\Models\Item;
use App\Models\QuotationItem;
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
            'vendor_quotation.quotation_ref',
            'vendor_quotation.created_at',
            'vendor_quotation.currency',
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
            'data' => $datavendor
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
            'title' =>'Item',
            'categorys' =>$categorys,
            'data' => $dataItem
        ];
        return view('admin.report.itemWise',$data);
    }

    public function quotationWise(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $customers = Customer::all();
        $select = [
            'items.item_name',
            'brands.brand_name',
            'customers.customer_name',
            'quotation_item.rate',
            'quotation_item.amount',
        ];
        $dataquotation = QuotationItem::select($select)
            ->join('quotations', 'quotations.id', '=', 'quotation_item.quotation_id')
            ->join('customers', 'customers.id', '=', 'quotations.customer_id')
            ->join('items', 'items.id', '=', 'quotation_item.item_id')
            ->join('brands', 'brands.id', '=', 'quotation_item.brand_id')
            ->where('quotations.customer_id',$customer_id);
        $dataquotation = $dataquotation->paginate($this->count);

        $data = [
            'title' =>'Quotation',
            'customers' =>$customers,
            'data' => $dataquotation
        ];
        return view('admin.report.quotationWise',$data);
    }

    public function inquiryDate(Request $request)
    {
        $start_date = $request->input('date_start', false);
        $end_date = $request->input('date_end', false);

        $items = Item::all();
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
        $datainquiry = Inquiry::select($select)
            ->join('inquiry_order', 'inquiry_order.inquiry_id','=','inquiries.id')
            ->join('items', 'items.id','=','inquiry_order.item_id')
            ->leftJoin('customers','customers.id','=','inquiries.customer_id')
            ->leftJoin('users','users.id','=','inquiries.user_id');
            #->get();

        if ($request->has('date_start') && !empty($request->input('date_start'))) $datainquiry = $datainquiry->where('inquiries.created_at', '>=', $start_date);
        if ($request->has('date_end') && !empty($request->input('data_end'))) $datainquiry = $datainquiry->where('inquiries.created_at', '<=', $end_date);

        $datainquiry = $datainquiry->paginate($this->count);

        $data = [
            'title' =>'Inquiries Date Wise',
            'items' =>$items,
            'data' => $datainquiry
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

    public function inquirySalePersonPdf($id)
    {
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
            ->where('users.id',$id)
            ->groupBy('inquiries.id')
            ->paginate($this->count);

        $data = [
            'title'      => 'Sales Person Inquiries',
            'data'       => $dataSale
        ];
        $date = "Inquiry-SalePerson-Report-". Carbon::now()->format('d-M-Y')  .".pdf";
        $pdf = PDF::loadView('admin.report.inquirySale-pdf', $data);
        return $pdf->download($date);
    }

    public function vendorQuotesPdf($id)
    {
        $select = [
            'items.item_name',
            'categories.category_name',
            'brands.brand_name',
            'vendor_quotation.project_name',
            'vendor_quotation.quotation_ref',
            'vendor_quotation.created_at',
            'vendor_quotation.currency',
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
            ->where('vendor_quotation_item.item_id',$id)
            ->orderBy('vendor_quotation.created_at','DESC')
            ->get();

        $data = [
            'title' =>'Reports',
            'data' => $datavendor
        ];
        $date = "Vendor-Report-". Carbon::now()->format('d-M-Y')  .".pdf";
        $pdf = PDF::loadView('admin.report.vendorQuote-pdf', $data);
        return $pdf->download($date);
    }

    public function quotationWisePdf($id)
    {
        $select = [
            'items.item_name',
            'brands.brand_name',
            'customers.customer_name',
            'quotation_item.rate',
            'quotation_item.amount',
        ];
        $dataquotation = QuotationItem::select($select)
            ->join('quotations', 'quotations.id', '=', 'quotation_item.quotation_id')
            ->join('customers', 'customers.id', '=', 'quotations.customer_id')
            ->join('items', 'items.id', '=', 'quotation_item.item_id')
            ->join('brands', 'brands.id', '=', 'quotation_item.brand_id')
            ->where('quotations.customer_id',$id)
            ->get();

        $data = [
            'title' =>'Quotation',
            'data' => $dataquotation
        ];
        $date = "Quotation-Report-". Carbon::now()->format('d-M-Y')  .".pdf";
        $pdf = PDF::loadView('admin.report.quotationWise-pdf', $data);
        return $pdf->download($date);
    }

    public function itemWisePdf($id)
    {

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
            ->where('items.category_id',$id)
            ->paginate($this->count);

        $data = [
            'title' =>'Reports',
            'data' => $dataItem
        ];
        $date = "Item-Report-". Carbon::now()->format('d-M-Y')  .".pdf";
        $pdf = PDF::loadView('admin.report.itemWise-pdf', $data);
        return $pdf->download($date);
    }

    public function inquiryDatePdf(Request $request)
    {
        $start_date = $request->input('date_start', false);
        $end_date = $request->input('date_end', false);

        $items = Item::all();
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
        $datainquiry = Inquiry::select($select)
            ->join('inquiry_order', 'inquiry_order.inquiry_id','=','inquiries.id')
            ->join('items', 'items.id','=','inquiry_order.item_id')
            ->leftJoin('customers','customers.id','=','inquiries.customer_id')
            ->leftJoin('users','users.id','=','inquiries.user_id');

        if ($request->has('date_start') && !empty($request->input('date_start'))) $datainquiry = $datainquiry->where('inquiries.created_at', '>=', $start_date);
        if ($request->has('date_end') && !empty($request->input('data_end'))) $datainquiry = $datainquiry->where('inquiries.created_at', '<=', $end_date);

        $datainquiry = $datainquiry->get();

        $data = [
            'title' =>'Inquiries Date Wise',
            'items' =>$items,
            'data' => $datainquiry
        ];
        $date = "Inquiry-Date-Report-". Carbon::now()->format('d-M-Y')  .".pdf";
        $pdf = PDF::loadView('admin.report.inquiryDate-pdf', $data);
        return $pdf->download($date);
    }
}
