<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'items';
    protected $fillable = ['item_name','category_id','item_description','brand_id','price','weight','height',
                           'unit','width','dimension','picture','created_at','updated_at'];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }

    public function order()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }
}
