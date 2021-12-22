<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationItemComparison extends Model
{
    use HasFactory;

    protected $table = 'quotation_comparison_item';
    protected $fillable = ['item_id','brand_id','quantity','unit','rate','rate_comparison','discount_rate',
        'discount_rate_comparison','amount','amount_comparison','quotation_id',
        'created_at','updated_at'];

    public function product()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }

    public function quotation()
    {
        return $this->hasOne(QuotationComparison::class, 'id', 'quotation_id');
    }
}
