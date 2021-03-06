<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Inquiry;
use App\Models\InquiryDocument;
use App\Models\InquiryOrder;
use App\Models\Item;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class InquiryController extends Controller
{
    public function index()
    {
        $select = [
            'customers.customer_name',
            'inquiries.id',
            'inquiries.project_name',
            'inquiries.date',
            'inquiries.timeline',
            'users.name as username',
            DB::raw("(
                CASE
                    WHEN `quotations`.`id` iS NULL
                        THEN 'open'
                    ELSE 'close'
                END
            ) as 'inquiry_status'")
        ];
        $auth = Auth::user()->id;
        $inquires = Inquiry::select($select)
            ->leftJoin('customers','customers.id','=','inquiries.customer_id')
            ->leftJoin('inquiry_documents','inquiry_documents.inquiry_id','=','inquiries.id')
            ->leftJoin('inquiry_order','inquiry_order.inquiry_id', '=', 'inquiries.id')
            ->leftJoin('brands','brands.id' ,'=', 'inquiry_order.brand_id')
            ->leftJoin('categories', 'categories.id' ,'=', 'inquiry_order.category_id')
            ->leftJoin('users', 'users.id' ,'=', 'inquiries.user_id')
            ->leftJoin('items', 'items.id' ,'=', 'inquiry_order.item_id')
            ->leftJoin('quotations', 'quotations.inquiry_id' ,'=', 'inquiries.id')
            ->where('inquiries.user_id', $auth)
            ->groupBy('inquiries.id')->paginate($this->count);

        $data = [
            'title'   => 'View Inquiries',
            'user'    => Auth::user(),
            'inquires'=> $inquires
        ];
        return view('sale.inquiry.view',$data);
    }

    public function open(Request $request)
    {
        $auth = Auth::user()->id;
        $select = [
            'customers.customer_name',
            'inquiries.id',
            'inquiries.project_name',
            'inquiries.date',
            'inquiries.timeline',
            'users.name as username',
            DB::raw("(
                CASE
                    WHEN `quotations`.`id` iS NULL
                        THEN 'open'
                    ELSE 'close'
                END
            ) as 'inquiry_status'"),
            DB::raw("COUNT(inquiry_order.id) as item_count")
        ];
        $inquires = Inquiry::select($select)
            ->leftJoin('customers','customers.id','=','inquiries.customer_id')
            ->leftJoin('inquiry_documents','inquiry_documents.inquiry_id','=','inquiries.id')
            ->leftJoin('inquiry_order','inquiry_order.inquiry_id', '=', 'inquiries.id')
            ->leftJoin('brands','brands.id' ,'=', 'inquiry_order.brand_id')
            ->leftJoin('categories', 'categories.id' ,'=', 'inquiry_order.category_id')
            ->leftJoin('users', 'users.id' ,'=', 'inquiries.user_id')
            ->leftJoin('items', 'items.id' ,'=', 'inquiry_order.item_id')
            ->leftJoin('quotations', 'quotations.inquiry_id' ,'=', 'inquiries.id')
            ->where('inquiries.user_id', $auth)
            ->whereNull('quotations.id')
            ->groupBy('inquiries.id','inquiry_order.inquiry_id');

        # Applying filters
        # 1. Applying sales person filter
        $request->sales_person && $inquires = $inquires->where('users.id', $request->sales_person);
        # 2. Applying customer name filter
        $request->customer_id && $inquires = $inquires->where('customers.id', $request->customer_id);
        # 3. Applying project name filter
        $request->project && $inquires = $inquires->where('quotations.project_name', 'LIKE', "%$request->project%");
        # 4. Applying start date and end date filter
        $start_date = $request->from;
        $end_date = $request->to;
        $request->from && $inquires = $inquires->where('quotations.created_at', '>', $start_date);
        $request->to && $inquires = $inquires->where('quotations.created_at', '<', $end_date);

        #We have separated the paginate function so we can apply all the filters before that
        $inquires = $inquires->paginate($this->count);


        $sale = User::where('user_role','sale')->get();

        $data = [
            'title'   => 'View Open Inquiries',
            'user'    => Auth::user(),
            'inquires'=> $inquires,
            'sales_people' => $sale,
            'request' => $request,
            'customers' =>Customer::all(),
            'reset_url' => route('inquiry.open.sale')
        ];
        return view('sale.inquiry.open',$data);
    }

    public function add()
    {
        $customers  = Customer::orderBy('id','DESC')->get();
        $categories = Category::orderBy('id','DESC')->get();
        $brands     = Brand::orderBy('id','DESC')->get();
        $items      = Item::orderBy('id','DESC')->get();

        $data = [
            'title'      => 'Submit Inquiry',
            'base_url'   => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'       => Auth::user(),
            'brands'     => $brands,
            'categories' => $categories,
            'customers'  => $customers,
            'items'      => $items
        ];
        return view('sale.inquiry.add', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'    => 'required',
            'project_name'   => 'required',
            'date'           => 'required',
            'timeline'       => 'required',
            'remarks'        => 'sometimes',
            'category_id'    => 'required|array',
            'category_id.*'  => 'required',
            'item_id'        => 'required|array',
            'item_id.*'      => 'required',
            'brand_id'       => 'required|array',
            'brand_id.*'     => 'required',
            'quantity'       => 'required|array',
            'quantity.*'     => 'required',
            'unit'           => 'required|array',
            'unit.*'         => 'required',
            'inquiry_file'   => 'required|array',
            'inquiry_file.*' => 'required|',
        ],[
            'customer_id.required'     => 'The customer field is required.',
            'project_name.required'    => 'The project name field is required.'
        ]);

        $files      = $request->inquiry_file;
        $items      = $request->item_id;
        $categories = $request->category_id;
        $brands     = $request->brand_id;
        $quantities = $request->quantity;
        $units      = $request->unit;

        $data = $request->all();
        $id=Auth::user()->id;
        $data['user_id']  = $id;
        $data['date']     = Carbon::parse($request->date)->format('Y-m-d');
        $data['timeline'] = Carbon::parse($request->timeline)->format('Y-m-d');
        $data['inquiry']  = Uuid::uuid4()->getHex();
        $inquiry = new Inquiry($data);
        $inquiry->save();

        $save = [];
        $save_document = [];

        foreach($files as $file) {
            $file_item = [
                'inquiry_id'   => $inquiry->id,
                'file_path'    => $this->uploadPDF($file)
            ];
            $save_document[] = (new InquiryDocument($file_item))->save();
        }

        foreach($categories as $index => $category) {
            $item_detail = Item::select('*')
                ->where('item_name',$items[$index])
                ->where('brand_id', $brands[$index])
                ->first();

            $inquiry_item = [
                'inquiry_id'   => $inquiry->id,
                'category_id'  => $category,
                'item_id'      => $item_detail->id,
                'brand_id'     => $brands[$index],
                'quantity'     => $quantities[$index],
                'unit'         => $units[$index]
            ];
            $save[] = (new InquiryOrder($inquiry_item))->save();
        }
        return redirect(
            route('inquiry.list.sale')
        )->with('success', 'Inquiry was added successfully!');
    }

    private function uploadPDF($file)
    {
        $filename  = Uuid::uuid4().".{$file->extension()}";
        $private_path = $file->storeAs('public/inquiry',$filename);
        $public_path  = Storage::url("inquiry/$filename");
        return $filename;
    }

    public function view($id)
    {
        $select=[
            'inquiries.id as unique',
            'inquiries.inquiry',
            'inquiries.project_name',
            'inquiries.created_at',
            'customers.attention_person',
            'customers.customer_name',
            'items.item_name',
            'items.item_description',
            'brands.brand_name',
            'users.name',
            'categories.category_name',
            'inquiry_order.quantity',
            'inquiry_order.unit',
        ];
        $inquires = Inquiry::select($select)
            ->leftJoin('customers','customers.id','=','inquiries.customer_id')
            ->leftJoin('inquiry_order','inquiry_order.inquiry_id', '=', 'inquiries.id')
            ->leftJoin('users','users.id' ,'=', 'inquiries.user_id')
            ->leftJoin('categories', 'categories.id' ,'=', 'inquiry_order.category_id')
            ->leftJoin('brands', 'brands.id' ,'=', 'inquiry_order.brand_id')
            ->leftJoin( 'items','items.id' ,'=', 'inquiry_order.item_id')
            ->where('inquiries.id',$id)
            ->get();

        $data = [
            'title'   => 'View Inquires',
            'user'    => Auth::user(),
            'inquiry'=> $inquires
        ];
        return view('sale.inquiry.item',$data);
    }

    public function pdfinquiry($id)
    {
        $select=[
            'inquiries.created_at',
            'inquiries.id as unique',
            'inquiries.inquiry',
            'inquiries.project_name',
            'customers.attention_person',
            'customers.customer_name',
            'items.item_name',
            'items.item_description',
            'brands.brand_name',
            'categories.category_name',
            'inquiry_order.quantity',
            'inquiry_order.unit'
        ];
        $inquires = Inquiry::select($select)
            ->leftJoin('customers','customers.id','=','inquiries.customer_id')
            ->leftJoin('inquiry_order','inquiry_order.inquiry_id', '=', 'inquiries.id')
            ->leftJoin('brands','brands.id' ,'=', 'inquiry_order.brand_id')
            ->leftJoin('categories', 'categories.id' ,'=', 'inquiry_order.category_id')
            ->leftJoin( 'items','items.id' ,'=', 'inquiry_order.item_id')
            ->where('inquiries.id',$id)
            ->get();
        $data = [
            'title'      => 'Inquiry Pdf',
            'base_url'   => env('APP_URL', 'http://omnibiz.local'),
            'user'       => Auth::user(),
            'inquiry'=> $inquires
        ];
        $date = "Inquiry-Invoice-". Carbon::now()->format('d-M-Y')  .".pdf";
        $pdf = PDF::loadView('sale.inquiry.pdf-invoice', $data);
        return $pdf->download($date);
    }

    public function ajaxFetchCategory(Request $request)
    {
        $request->validate([
            'category' => 'required'
        ]);
        $category_id = $request->category;
        $category_items = Item::select([DB::raw('DISTINCT item_name')])
            ->where('category_id', $category_id)
            ->get();
        return response($category_items, 200);
    }

    public function ajaxFetchItem(Request $request)
    {
        $request->validate([
            'item' => 'required'
        ]);
        $item_name = $request->item;
        $item_categories = Item::select(['brands.brand_name', 'brands.id'])
            ->join('brands', 'brands.id', '=', 'items.brand_id')
            ->where('items.item_name','like',"%{$item_name}%")
            ->groupBy('brands.id')
            ->get();
        return response($item_categories, 200);
    }

    public function fetchDocuments($id)
    {
        $documents = InquiryDocument::select('file_path', 'id')->where('inquiry_id', $id)->get();
        return view('sale.inquiry.file-download', compact('documents'));
    }

    public function downloadDocument($id)
    {
        $document = InquiryDocument::find($id);
        if (!$document) return redirect(route('inquiry.list.sale'))->with('error', 'Document not found!');
        return Storage::download("public/inquiry/{$document->file_path}");
    }
}
