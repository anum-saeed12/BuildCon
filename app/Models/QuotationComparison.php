<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationComparison extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'quotation_comparison';
    protected $fillable = ['user_id','customer_id','inquiry_id','project_name','quotation','date','discount','discount_comparison','terms_condition',
        'currency','total','total_comparison','created_at','updated_at'];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(QuotationItemComparison::class,'quotation_id','id')->with('product')->with('brand');
    }
}
