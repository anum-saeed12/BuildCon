<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::orderBy('id','DESC')->whereNull('categories.deleted_at')->paginate($this->count);
        $data = [
            'title'     => 'View Categories',
            'user'      => Auth::user(),
            'categories'=> $category
        ];
        return view('admin.category.view',$data);
    }

    public function add()
    {
        $data = [
            'title'    => 'Add Category',
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'     => Auth::user(),
        ];
        return view('admin.category.add', $data);
    }

    public function edit($id)
    {
        $category = Category::find($id);
        $categories = Category::orderBy('id','DESC')->paginate($this->count);
        $data = [
            'title'      => 'Update Category',
            'base_url'   => env('APP_URL', 'http://127.0.0.1:8000'),
            'user'       => Auth::user(),
            'category'   => $category,
            'categories' => $categories,
        ];
        return view('admin.category.edit', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required'
        ], [
                'category_name.required'  => 'The category name field is required.'
            ]
        );

        $exist = Category::where('category_name',$request->category_name)->first();

        if($exist)
        {
            return redirect(
                route('category.list.admin')
            )->with('success', 'Category already exists!!');
        }

        $data    =  $request->all();
        $brand   = new Category($data);

        $brand->save();

        return redirect(
            route('category.list.admin')
        )->with('success', 'Category was added successfully!');
    }

    public function update (Request $request,$id)
    {
        $category = Category::find($id);

        $request->validate([
            'category_name'          => "required|unique:App\Models\Category,category_name,{$id}"
        ],[
            'category_name.required' => 'The category name field is required.',
        ]);

        $request->input('category_name')   &&  $category->category_name    = $request->input('category_name');
        $category->save();

        return redirect(
            route('category.list.admin')
        )->with('success', 'Category updated successfully!');
    }

    public function ajaxFetch(Request $request)
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

    public function delete($id)
    {
        $category = Category::find($id)->delete();
        return redirect(
            route('category.list.admin')
        )->with('success', 'Brand deleted successfully!');
    }
}
