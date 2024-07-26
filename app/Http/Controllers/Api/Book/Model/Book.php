<?php

namespace App\Http\Controllers\Api\Book\Model;

use App\Http\Controllers\Api\BookPurchase\Model\BookPurchase;
use App\Http\Controllers\Api\BookReservation\Model\BookReservation;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Ramsey\Uuid\Uuid;

class Book extends Model
{
    use HasFactory, HasUuids,  SoftDeletes, FilterQueryString;

    protected $table = 'books';
    protected $primaryKey = 'book_id';
    protected $filters = [
        'sort',
        'like',
        'in',

    ];
    protected $fillable = [
        'book_status',
        'purchase_id',


    ];
    public function bookPurchaseForeign()
    {
        return $this->belongsTo(BookPurchase::class, 'purchase_id');
    }
    protected $dates = ['deleted_at'];

}