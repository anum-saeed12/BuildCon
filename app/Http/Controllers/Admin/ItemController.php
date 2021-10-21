<?php

namespace App\Http\Controllers\Admin;

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
        $items = Item::select('*')
                       ->leftJoin('categories', 'categories.id', '=', 'items.category_id')
                       ->leftJoin('brands', 'brands.id', '=', 'items.brand_id')
                       ->paginate($this->count);
        $data = [
            'title'   => 'Items',
            'user'    => Auth::user(),
            'items'   => $items
        ];
        return view('admin.item.view',$data);
    }

    public function add()
    {
        $categories = Category::orderBy('id','DESC')->paginate($this->count);
        $brands     = Brand::orderBy('id','DESC')->paginate($this->count);

        $data = [
            'title'    => 'Add Item',
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'     => Auth::user(),
            'brands'    => $brands,
            'categories' => $categories,
        ];
        return view('admin.item.add', $data);
    }

    public function edit($id)
    {
        $data = [
            'title'    => 'Update Item',
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'     => Auth::user(),
        ];
        return view('admin.item.edit', $data);
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
            route('item.list.admin')
        )->with('success', 'Item was added successfully!');
    }


    private function uploadPicture($picture)
    {
        $picturename  = Uuid::uuid4().".{$picture->extension()}";
        $private_path = $picture->storeAs('public/images',$picturename);
        $public_path  = Storage::url("picture/$picturename");
        return $picturename;

    }

}