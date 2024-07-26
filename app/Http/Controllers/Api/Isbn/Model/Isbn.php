<?php

namespace App\Http\Controllers\Api\Isbn\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Ramsey\Uuid\Uuid;

class Isbn extends Model
{
    use HasFactory, HasUuids, SoftDeletes, FilterQueryString;

    protected $table = 'isbns';
    protected $primaryKey = 'isbn_id';
    protected $fillable = [
        'isbn'
    ];
    protected $filters = [
        'sort',
        'like',
        'in',
    ];

    protected $dates = ['deleted_at'];
}
