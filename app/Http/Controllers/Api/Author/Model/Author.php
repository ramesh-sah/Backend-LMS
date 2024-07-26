<?php

namespace App\Http\Controllers\Api\Author\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Ramsey\Uuid\Uuid;

class Author extends Model
{
    use HasFactory, HasUuids, SoftDeletes, FilterQueryString;
    protected $table = 'authors';
    protected $primaryKey = 'author_id';
    protected $fillable = [
        'author_first_name',
        'author_middle_name',
        'author_last_name',

    ];
}
