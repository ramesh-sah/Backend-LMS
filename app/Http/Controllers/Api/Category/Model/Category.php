<?php

namespace App\Http\Controllers\Api\Category\Model;

use App\Http\Controllers\Api\Book\Model\Book;
use App\Http\Controllers\Api\BookPurchase\Model\BookPurchase;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Ramsey\Uuid\Uuid;

class Category extends Model
{
    use HasFactory, HasUuids,  SoftDeletes, FilterQueryString;

    protected $table = 'categories';
    protected $primaryKey = 'category_id';
    protected $fillable = [
        'category_name',

    ];
    protected $filters = [
        'sort',
        'like',
        'in',
    ];
    protected $dates = ['deleted_at'];
}
