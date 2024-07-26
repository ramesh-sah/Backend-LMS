<?php

namespace App\Http\Controllers\Api\Barcode\Model;

use App\Http\Controllers\Api\BookPurchase\Model\BookPurchase;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Ramsey\Uuid\Uuid;

class Barcode extends Model
{
    use HasFactory , HasUuids, SoftDeletes, FilterQueryString;

    protected $table = 'barcodes';
    protected $primaryKey = 'barcode_id';
    protected $filters = [
        'sort',
        'like',
        'in',
    ];


    protected $fillable = [
      'barcode',
    ];

    protected $dates = ['deleted_at'];

    
}
