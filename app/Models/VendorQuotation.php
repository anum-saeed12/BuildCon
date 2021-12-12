<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorQuotation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'vendor_quotation';
    protected $fillable = ['user_id','vendor_id','project_name','vendor_quotation','total','date','quotation_ref','quotation_pdf','created_at','updated_at'];

    public function vendor()
    {
        return $this->hasOne(Vendor::class, 'id', 'vendor_id');
    }

}
