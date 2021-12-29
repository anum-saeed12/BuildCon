<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Item;
use App\Models\UserCategory;
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
                       ->leftJoin('brands', 'brands.id', '=', 'items.brand_id')
                       ->whereIn('items.category_id', UserCategory::select('category_id as id')->where('user_id', Auth::user()->id)->get())
                       ->paginate($this->count);
        $data = [
            'title'   => 'Items',
            'user'    => Auth::user(),
            'items'   => $items
        ];
        return view('team.item.view',$data);
    }

    public function add()
    {
        $category_select = [
            'categories.category_name',
            'categories.id',
        ];
        $categories = Category::select($category_select)
            ->join('items', 'items.category_id','=','categories.id')->orderBy('id','DESC')
            ->whereIn('items.category_id', UserCategory::select('category_id as id')->where('user_id', Auth::id())->get())
            ->groupBy('categories.category_name')
            ->get();
        $brands     = Brand::orderBy('id','DESC')->paginate($this->count);

        $data = [
            'title'    => 'Add Item',
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'     => Auth::user(),
            'brands'    => $brands,
            'categories' => $categories,
        ];
        return view('team.item.add', $data);
    }

    public function edit($id)
    {
        $data = [
            'title'    => 'Update Item',
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'     => Auth::user(),
        ];
        return view('team.item.edit', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name'       => 'required',
            'category_id'     => 'required|exists:App/Models/Category,id',
            'item_description'=> 'required',
            'brand_id'        => 'required|exists:App/Models/Brand,id',
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
            route('item.list.team')
        )->with('success', 'Item was added successfully!');
    }

    public function ajaxFetch(Request $request)
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

    private function uploadPicture($picture)
    {
        $picturename  = Uuid::uuid4().".{$picture->extension()}";
        $private_path = $picture->storeAs('public/images',$picturename);
        $public_path  = Storage::url("picture/$picturename");
        return $picturename;
    }

}
