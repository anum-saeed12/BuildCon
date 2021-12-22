<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'quotations';
    protected $fillable = ['user_id','customer_id','inquiry_id','project_name',
        'quotation','date','discount','terms_condition','currency','total','created_at','updated_at'];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class,'quotation_id','id')->with('product')->with('brand');
    }

}
