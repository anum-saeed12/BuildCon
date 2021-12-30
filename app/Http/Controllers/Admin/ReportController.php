<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Inquiry;
use App\Models\InquiryOrder;
use App\Models\Item;
use App\Models\Quotation;
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
        $item_name = $request->input('item');
        $items     = Item::select([
            DB::raw("DISTINCT item_name")
        ])->orderBy('id','DESC')->get();
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
            'users.user_role'
        ];
        $datavendor = VendorQuotationItem::select($select)
            ->join('items', 'items.id', '=', 'vendor_quotation_item.item_id')
            ->leftJoin('categories', 'categories.id', '=', 'vendor_quotation_item.category_id')
            ->leftJoin('brands', 'brands.id', '=', 'vendor_quotation_item.brand_id')
            ->leftJoin('vendor_quotation', 'vendor_quotation.id', '=', 'vendor_quotation_item.vendor_quotation_id')
            ->leftJoin('users', 'users.id', '=', 'vendor_quotation.user_id')
            ->leftJoin('vendors', 'vendors.id', '=', 'vendor_quotation.vendor_id')
            ->where('items.item_name',$item_name)
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
            'title' =>'Items by Category',
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
            'users.name as username',
            'users.user_role',
            'customers.customer_name',
            'quotations.project_name',
            'quotations.id as quotation_id',
            'quotations.created_at',
            'quotations.date',
            'quotations.total',
            DB::raw("COUNT(quotation_item.item_id) as total_items")
        ];
        $data_quotation = Quotation::select($select)
            ->join('quotation_item', 'quotation_item.quotation_id','=','quotations.id')
            ->join('items', 'items.id','=','quotation_item.item_id')
            ->leftJoin('customers','customers.id','=','quotations.customer_id')
            ->leftJoin('users','users.id','=','quotations.user_id')
            ->where('quotations.customer_id',$customer_id)
            ->groupBy('quotations.id');
        $data_quotation = $data_quotation->paginate($this->count);

        $data = [
            'title' =>'Quotation',
            'customers' =>$customers,
            'data' => $data_quotation
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
        $data_inquiry = Inquiry::select($select)
            ->join('inquiry_order', 'inquiry_order.inquiry_id','=','inquiries.id')
            ->join('items', 'items.id','=','inquiry_order.item_id')
            ->leftJoin('customers','customers.id','=','inquiries.customer_id')
            ->leftJoin('users','users.id','=','inquiries.user_id')
            ->groupBy('inquiries.id');
            #->get();

        if ($request->has('date_start') && !empty($request->input('date_start'))) $data_inquiry = $data_inquiry->where('inquiries.created_at', '>=', "{$start_date} 00:00:00");
        if ($request->has('date_end') && !empty($request->input('date_end'))) $data_inquiry = $data_inquiry->where('inquiries.created_at', '<=', "{$end_date} 23:59:59");
        $data_inquiry = $data_inquiry->paginate($this->count);

        $data = [
            'title' =>'Inquiries Date Wise',
            'items' => $items,
            'data' => $data_inquiry
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
            DB::raw("COUNT(inquiry_order.item_id) as total_items"),
        ];
        $dataSale = Inquiry::select($select)
            ->join('inquiry_order', 'inquiry_order.inquiry_id','=','inquiries.id')
            ->join('items', 'items.id','=','inquiry_order.item_id')
            ->leftJoin('customers','customers.id','=','inquiries.customer_id')
            ->leftJoin('users','users.id','=','inquiries.user_id')
            ->where('users.id',$sale_id)
            ->groupBy('inquiries.id')
            ->paginate($this->count);

        $total_query = Inquiry::select($select)
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
            'users.user_role'
        ];
        $data_vendor = VendorQuotationItem::select($select)
            ->join('items', 'items.id', '=', 'vendor_quotation_item.item_id')
            ->leftJoin('categories', 'categories.id', '=', 'vendor_quotation_item.category_id')
            ->leftJoin('brands', 'brands.id', '=', 'vendor_quotation_item.brand_id')
            ->leftJoin('vendor_quotation', 'vendor_quotation.id', '=', 'vendor_quotation_item.vendor_quotation_id')
            ->leftJoin('users', 'users.id', '=', 'vendor_quotation.user_id')
            ->leftJoin('vendors', 'vendors.id', '=', 'vendor_quotation.vendor_id')
            ->where('items.item_name',$id)
            ->orderBy('vendor_quotation.created_at','DESC')
            ->get();

        $data = [
            'title' =>'Reports',
            'data' => $data_vendor
        ];
        $date = "Vendor-Report-". Carbon::now()->format('d-M-Y')  .".pdf";
        $pdf = PDF::loadView('admin.report.vendorQuote-pdf', $data);
        return $pdf->download($date);
    }

    public function quotationWisePdf($id)
    {
        $select = [
            'users.name as username',
            'users.user_role',
            'customers.customer_name',
            'quotations.project_name',
            'quotations.id as quotation_id',
            'quotations.created_at',
            'quotations.date',
            'quotations.total',
            DB::raw("COUNT(quotation_item.item_id) as total_items")
        ];
        $data_quotation = Quotation::select($select)
            ->join('quotation_item', 'quotation_item.quotation_id','=','quotations.id')
            ->join('items', 'items.id','=','quotation_item.item_id')
            ->leftJoin('customers','customers.id','=','quotations.customer_id')
            ->leftJoin('users','users.id','=','quotations.user_id')
            ->where('quotations.customer_id',$id)
            ->groupBy('quotations.id');

        $data_quotation = $data_quotation->get();

        $data = [
            'title' =>'Quotation Wise',
            'data' => $data_quotation
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
            'items.created_at',
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
        $data_inquiry = Inquiry::select($select)
            ->join('inquiry_order', 'inquiry_order.inquiry_id','=','inquiries.id')
            ->join('items', 'items.id','=','inquiry_order.item_id')
            ->leftJoin('customers','customers.id','=','inquiries.customer_id')
            ->leftJoin('users','users.id','=','inquiries.user_id')
            ->groupBy('inquiries.id');

        if ($request->has('date_start') && !empty($request->input('date_start'))) $data_inquiry = $data_inquiry->where('inquiries.created_at', '>=', "{$start_date} 00:00:00");
        if ($request->has('date_end') && !empty($request->input('date_end'))) $data_inquiry = $data_inquiry->where('inquiries.created_at', '<=', "{$end_date} 23:59:59");

        $data_inquiry = $data_inquiry->get();

        $data = [
            'title' =>'Inquiries Date Wise',
            'data' => $data_inquiry
        ];
        $date = "Inquiry-Date-Report-". Carbon::now()->format('d-M-Y')  .".pdf";
        $pdf = PDF::loadView('admin.report.inquiryDate-pdf', $data);
        return $pdf->download($date);
    }
}
