<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationItem extends Model
{

    use HasFactory;

    protected $table = 'quotation_item';
    protected $fillable = ['item_id','category_id','brand_id','quantity','unit','rate','discount_rate','amount','quotation_id',
        'created_at','updated_at'];

    public function product()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }
    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function quotation()
    {
        return $this->hasOne(Quotation::class, 'id', 'quotation_id');
    }
}
