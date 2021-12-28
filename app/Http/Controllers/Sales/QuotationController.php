<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\Inquiry;
use App\Models\InquiryOrder;
use App\Models\Item;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class QuotationController extends Controller
{
    public function customer()
    {
        $select = [
            'quotations.id as quotation_id',
            'customers.customer_name',
            'quotations.project_name',
            'quotations.date',
            'quotations.total',
            'quotations.terms_condition',
            'users.name'
        ];
        $quotations = Quotation::select($select)
            ->where('quotations.user_id',Auth::user()->id)
            ->leftJoin('quotation_item', 'quotation_item.quotation_id', '=', 'quotations.id')
            ->leftJoin('brands', 'brands.id', '=', 'quotation_item.brand_id')
            ->leftJoin('items', 'items.id', '=', 'quotation_item.item_id')
            ->leftJoin('users','users.id','=','quotations.user_id')
            ->leftJoin('customers', 'customers.id', '=', 'quotations.customer_id')
            ->groupBy('quotations.id')->paginate($this->count);

        $data = [
            'title'     => 'Quotations',
            'user'      => Auth::user(),
            'quotations' => $quotations
        ];
        return view('sale.quotation.customer',$data);
    }

    public function view ($id)
    {
        $select=[
            'quotations.quotation',
            'quotations.project_name',
            'quotations.total',
            'quotations.currency',
            'quotations.discount',
            'quotations.created_at as creationdate',
            'quotations.id as unique',
            'categories.category_name',
            'items.item_name',
            'items.item_description',
            'brands.brand_name',
            'customers.customer_name',
            'customers.attention_person',
            'quotation_item.quantity',
            'quotation_item.rate',
            'quotation_item.unit',
            'quotation_item.discount_rate',
            'quotation_item.amount'
        ];
        $quotation = Quotation::select($select)
            ->where('quotations.id',$id)
            ->leftJoin('quotation_item', 'quotation_item.quotation_id', '=', 'quotations.id')
            ->leftJoin('brands', 'brands.id', '=', 'quotation_item.brand_id')
            ->leftJoin('items', 'items.id', '=', 'quotation_item.item_id')
            ->leftJoin('categories', 'categories.id', '=', 'items.category_id')
            ->leftJoin('customers', 'customers.id', '=', 'quotations.customer_id')
            ->get();
        $data = [
            'title'      => 'Quotations',
            'base_url'   => env('APP_URL', 'http://omnibiz.local'),
            'user'       => Auth::user(),
            'quotation'  => $quotation
        ];
        return view('sale.quotation.item', $data);
    }

    public function pdfquotation($id)
    {
        $select=[
            'items.item_name',
            'items.item_description',
            'items.category_id',
            'brands.brand_name',
            'categories.category_name',
            'quotation_item.quantity',
            'quotation_item.amount',
            'quotation_item.unit',
            'quotation_item.rate',
            'quotation_item.discount_rate',
            'categories.category_name',
        ];

        $quotation_select = [
            'quotations.quotation',
            'quotations.id',
            'quotations.project_name',
            'quotations.total',
            'quotations.currency',
            'quotations.discount',
            'customers.customer_name',
            'customers.attention_person',
        ];
        $quotation = Quotation::select($quotation_select)
            ->join('customers', 'customers.id','=','quotations.customer_id')
            ->where('quotations.id', $id)
            ->first();

        if (!isset($quotation->id)) return redirect()->back()->with('error', 'Quotation not found');

        $quotation->items = QuotationItem::select($select)
            ->leftJoin('brands', 'brands.id', '=', 'quotation_item.brand_id')
            ->leftJoin('items', 'items.id', '=', 'quotation_item.item_id')
            ->leftJoin('categories', 'categories.id', '=', 'items.category_id')
            ->orderBy('items.category_id','ASC')
            ->where('quotation_item.quotation_id',$quotation->id)
            ->groupBy('items.id')
            ->get();
        $quotation->creation = \Illuminate\Support\Carbon::createFromTimeStamp(strtotime($quotation->created_at))->format('d-M-Y');

        $data = [
            'title'      => 'Quotation Pdf',
            'base_url'   => env('APP_URL', 'http://omnibiz.local'),
            'user'       => Auth::user(),
            'quotation'  => $quotation
        ];
        $date = "Quotation-Invoice-". Carbon::now()->format('d-M-Y')  .".pdf";
        $pdf = PDF::loadView('admin.quotation.pdf-invoice', $data);
        return $pdf->download($date);
    }
}
