<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquiry extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'inquiries';
    protected $fillable = ['user_id','customer_id','project_name','inquiry','date','timeline','remarks','created_at','updated_at'];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
    public function inquiryitem()
    {
        return $this->hasMany(InquiryOrder::class, 'inquiry_id', 'id');
    }
}
