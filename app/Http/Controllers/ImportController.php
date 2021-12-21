<?php

namespace App\Http\Controllers;

use App\Exports\ItemsExport;
use App\Imports\ItemsImport;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ImportedItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Ramsey\Uuid\Uuid;

class ImportController extends Controller
{
    public function importItem(Request $request)
    {
        $request->validate([
            'itemsFile' => 'file'
        ]);
        $file = $this->uploadFile($request->file('itemsFile'));
        $itemImport = new ItemsImport();
        # Start Excel Upload
        $imported = Excel::import($itemImport, $file);

        $batch_id = $itemImport->batch_id;

        return redirect(route('import.report', $batch_id));
    }

    public function uploadFile($file)
    {
        $filename  = date('Ymdhis')."Imported".ucfirst($file->getClientOriginalName());
        $directory = "item_imports";
        $private_path = $file->storeAs("public/{$directory}",$filename);
        $filepath = "{$directory}/{$filename}";
        return Storage::disk('local')->path($private_path);
        #return "/item_imports/{$filename}";
    }

    public function viewImport($batch_id)
    {
        $imported_data = ImportedItem::where('batch_id', $batch_id);
        $imported_data->firstOrFail();
        $data = [
            'title'         => 'View Import Data ',
            'user'          => Auth::user(),
            'imported_data' => $imported_data->paginate($this->count),
            'batch_id'      => $batch_id
        ];
        return view('admin.item.importView',$data);
    }

    public function approve(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|exists:App\Models\ImportedItem,batch_id',
        ]);
        $imported_items = ImportedItem::where('batch_id', $request->batch_id)->get();

        $categories = [];
        $brands = [];
        $products = [];

        $data_to_be_inserted = [];
        $data_to_be_updated = [];

        $updated = [];

        $created_categories = [];
        $created_brands = [];

        $existing_items = Item::all();
        foreach ($existing_items as $_item) $products[$_item->item_name] = $_item->id;


        $existing_categories = Category::all();
        foreach ($existing_categories as $_category) $categories[$_category->category_name] = $_category->id;

        $existing_brands = Brand::all();
        foreach ($existing_brands as $_brand) $brands[$_brand->brand_name] = $_brand->id;

        foreach($imported_items as $item) {
            $item_name = $item->item_name;
            $product_exists = isset($products[$item_name]);
            $category_exists = isset($categories[$item->category_name]);
            $brand_exists = isset($brands[$item->brand_name]);

            # 1st step:
            # Create a new category if category doesnt exist
            $category_id = $category_exists ? $categories[$item->category_name] : Category::add($item->category_name);
            $category_exists || $created_categories[] = $category_id;
            $category_exists || $categories[$item->category_name] = $category_id;

            # 2nd step:
            # Create a new brand if brand doesnt exist
            $brand_id = $brand_exists ? $brands[$item->brand_name] : Brand::add($item->brand_name);
            $brand_exists || $created_brands[] = $brand_id;
            $brand_exists || $brands[$item->brand_name] = $brand_id;

            if (!$product_exists) {
                # Checks if the product is in relation with the category and the brand
                $exists = Item::select('id')
                    ->where('item_name', $item->item_name)
                    ->where('category_id', $category_id)
                    ->where('brand_id', $brand_id)
                    ->first();
                if (!$exists) {
                    $new_item = [];
                    $new_item['item_name'] = $item->item_name;
                    $new_item['category_id'] = $category_id;
                    $new_item['brand_id'] = $brand_id;
                    $new_item['item_description'] = $item->item_description;
                    $new_item['unit'] = $item->unit;
                    $new_item['price'] = $item->price;
                    $new_item['weight'] = $item->weight;
                    $new_item['dimension'] = $item->dimension;
                    $new_item['height'] = $item->height;
                    $new_item['width'] = $item->width;
                    # Append the new item to the insert array
                    $data_to_be_inserted[] = $new_item;
                    continue;
                }
                # If the item exists
                $updated_item = [];
                $new_item['item_name'] = $item->item_name;
                $new_item['category_id'] = $category_id;
                $new_item['brand_id'] = $brand_id;
                $updated_item['item_description'] = $item->item_description;
                $updated_item['unit'] = $item->unit;
                $updated_item['price'] = $item->price;
                $updated_item['weight'] = $item->weight;
                $updated_item['dimension'] = $item->dimension;
                $updated_item['height'] = $item->height;
                $updated_item['width'] = $item->width;
                # Append the new item to the insert array
                $data_to_be_updated[] = $updated_item;
            }
        }
        # Insert the newly created items
        $insert_new_items = Item::insert($data_to_be_inserted);

        # Update the items
        foreach ($data_to_be_updated as $updatable_item)
        {
            $data_to_update = Item::where('product_name', $updatable_item->item_name)
                ->where('category_id', $updatable_item->category_id)
                ->where('brand_id', $updatable_item->brand_id);
            unset($updatable_item['product_name']);
            unset($updatable_item['category_id']);
            unset($updatable_item['brand_id']);
            $updated[] = $data_to_update->update($updatable_item);
        }

        $update_batch = ImportedItem::where('batch_id', $request->batch_id)->update(['imported' => 1]);

        $data = [
            'created' => [
                'items' => $data_to_be_inserted,
                'created_categories' => $created_categories,
                'created_brands' => $created_brands
            ],
            'updated' => $updated
        ];

        return redirect(route('item.list.admin'))->with('success', 'Import successful!')->with('stats', $data);
    }

    public function exportItem()
    {
        $filename = "Items_" . date('M_d_Y') . ".xlsx";
        return Excel::download(new ItemsExport(), $filename);
    }
}
