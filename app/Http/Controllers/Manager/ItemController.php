<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class ItemController extends Controller
{
    public function index()
    {
        $select=[
            'items.id as item_id',
            'items.item_name',
            'categories.category_name',
            'brands.brand_name',
            'items.weight',
            'items.picture',
            'items.price',
            'items.dimension',
            'items.height',
            'items.width',
            'items.unit',
            'items.item_description',
        ];
        $items = Item::select($select)
            ->leftJoin('categories', 'categories.id', '=', 'items.category_id')
            ->leftJoin('brands', 'brands.id', '=', 'items.brand_id')->whereNull('items.deleted_at')
            ->paginate($this->count);
        $data = [
            'title'   => 'Items',
            'user'    => Auth::user(),
            'items'   => $items
        ];
        return view('manager.item.view',$data);
    }

    public function add()
    {
        $categories = Category::orderBy('id','DESC')->get();
        $brands     = Brand::orderBy('id','DESC')->get();

        $data = [
            'title'      => 'Add Item',
            'base_url'   => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'       => Auth::user(),
            'brands'     => $brands,
            'categories' => $categories
        ];
        return view('manager.item.add', $data);
    }

    public function edit($id)
    {
        $select=[
            'items.id as item_id',
            'items.item_name',
            'categories.category_name',
            'brands.brand_name',
            'items.weight',
            'items.picture',
            'items.price',
            'items.dimension',
            'items.height',
            'items.width',
            'items.unit',
            'items.item_description',
        ];
        $data = Item::select($select)
            ->where('items.id',$id)
            ->leftJoin('brands','brands.id' ,'=', 'items.brand_id')
            ->leftJoin('categories', 'categories.id' ,'=', 'items.category_id')
            ->first();
        $categories = Category::orderBy('id','DESC')->get();
        $brands     = Brand::orderBy('id','DESC')->get();

        $data = [
            'title'      => 'Update Item',
            'base_url'   => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'       => Auth::user(),
            'data'       => $data,
            'brands'     => $brands,
            'categories' => $categories
        ];
        return view('manager.item.edit', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name'       => 'required',
            'category_id'     => 'required|exists:App\Models\Category,id',
            'item_description'=> 'required',
            'brand_id'        => 'required|exists:App\Models\Brand,id',
            'weight'          => 'required',
            'height'          => 'required',
            'price'           => 'required',
            'unit'            => 'required',
            'width'           => 'required',
            'dimension'       => 'required',
            'picture'         => 'required|file'
        ],[
            'category_id.required' => 'The category field is required.',
            'item_name.required' => 'The item name field is required.',
            'brand_id.required' => 'The brand field is required.',
        ]);

        $item    =  $request->all();
        $item['picture']     =  $this->uploadPicture($request->file('picture'));
        $user = new Item($item);

        $user->save() ;
        return redirect(
            route('item.list.manager')
        )->with('success', 'Item was added successfully!');
    }

    public function update(Request $request,$id)
    {
        $item = Item::find($id);
        $request->validate([
            'item_name'       => 'required',
            'category_id'     => 'required|exists:App\Models\Category,id',
            'item_description'=> 'required',
            'brand_id'        => 'required|exists:App\Models\Brand,id',
            'weight'          => 'required',
            'height'          => 'required',
            'price'           => 'required',
            'unit'            => 'required',
            'width'           => 'required',
            'dimension'       => 'required',
            'picture'         => 'required|file'
        ],[
            'category_id.required' => 'The category field is required.',
            'item_name.required' => 'The item name field is required.',
            'brand_id.required' => 'The brand field is required.',
        ]);

        #$item    =  $request->all();
        #$item['picture']     =  $this->uploadPicture($request->file('picture'));

        $request->input('item_name')        &&  $item->item_name        = $request->input('item_name');
        $request->input('category_id')      &&  $item->category_id      = $request->input('category_id');
        $request->input('item_description') &&  $item->item_description = $request->input('item_description');
        $request->input('brand_id')         &&  $item->brand_id         = $request->input('brand_id');
        $request->input('weight')           &&  $item->weight           = $request->input('weight');
        $request->input('height')           &&  $item->height           = $request->input('height');
        $request->input('price')            &&  $item->price            = $request->input('price');
        $request->input('unit')             &&  $item->unit             = $request->input('unit');
        $request->input('width')            &&  $item->width            = $request->input('width');
        $request->input('dimension')        &&  $item->dimension        = $request->input('dimension');
        $item->save();

        return redirect(
            route('item.list.manager')
        )->with('success', 'Item was updated successfully!');
    }

    public function ajaxFetch(Request $request)
    {
        $request->validate([
            'item' => 'required'
        ]);
        $item_name = $request->item;
        if (!$request->has('brand')) {
            $item_categories = Item::select(['brands.brand_name', 'brands.id'])
                ->join('brands', 'brands.id', '=', 'items.brand_id')
                #OLD: ->where('items.item_name', 'like', "%{$item_name}%")
                ->where('items.item_name', 'like', "{$item_name}")
                ->groupBy('brands.id')
                ->get();
            return response($item_categories, 200);
        }

        $brand = $request->brand;
        $item_info = Item::select(['items.unit', 'items.price'])
            ->where('items.item_name', 'like', "{$item_name}")
            ->where('items.brand_id', $brand)
            ->first();
        return response($item_info, 200);
    }

    private function uploadPicture($picture)
    {
        $picturename  = Uuid::uuid4().".{$picture->extension()}";
        $private_path = $picture->storeAs('public/images',$picturename);
        $public_path  = Storage::url("picture/$picturename");
        return $picturename;
    }

    public function delete($id)
    {
        $item= Item::find($id);
        $item->delete();
        return redirect(
            route('item.list.manager')
        )->with('success', 'Item deleted successfully!');
    }

}
