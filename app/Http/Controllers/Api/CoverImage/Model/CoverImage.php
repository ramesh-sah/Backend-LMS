<?php

namespace App\Http\Controllers\Api\CoverImage\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoverImage extends Model
{
  use HasFactory, HasUuids,  SoftDeletes,FilterQueryString;
    protected $table = 'cover_images';
    protected $primaryKey = 'image_id';
    protected $filters = [
        'sort',
        'like',
        'in',
    ];
    
    
    protected $fillable = [
        'link'
    ];
    protected $dates = ['deleted_at'];
    
    

   
}
