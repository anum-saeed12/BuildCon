<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'vendors';
    protected $fillable = ['vendor_name','attended_person','address','country','created_at','updated_at'];
}
