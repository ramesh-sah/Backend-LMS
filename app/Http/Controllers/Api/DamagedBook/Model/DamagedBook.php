<?php

namespace App\Http\Controllers\Api\DamagedBook\Model;

use App\Http\Controllers\Api\Book\Model\Book;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Ramsey\Uuid\Uuid;

class DamagedBook extends Model
{
    use HasFactory, HasUuids,  SoftDeletes, FilterQueryString;

    protected $table = 'damaged_books';
    protected $primaryKey = 'damaged_book_id';
    protected $filters = [
        'sort',
        'like',
        'in',
    ];
    protected $fillable = [
        'book_id',
        
    ];
    public function bookForeign()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
    protected $dates = ['deleted_at'];
    
}
