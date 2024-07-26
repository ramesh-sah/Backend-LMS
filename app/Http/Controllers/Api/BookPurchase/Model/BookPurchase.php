<?php

namespace App\Http\Controllers\Api\BookPurchase\Model;

use App\Http\Controllers\Api\Author\Model\Author;
use App\Http\Controllers\Api\Barcode\Model\Barcode;
use App\Http\Controllers\Api\BookOnline\Model\BookOnline;
use App\Http\Controllers\Api\Category\Model\Category;
use App\Http\Controllers\Api\CoverImage\Model\CoverImage;
use App\Http\Controllers\Api\Isbn\Model\Isbn;
use App\Http\Controllers\Api\Publisher\Model\Publishers;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Ramsey\Uuid\Uuid;

class BookPurchase extends Model
{
    use HasFactory, HasUuids,  SoftDeletes, FilterQueryString;

    protected $table = 'book_purchases';
    protected $primaryKey = 'purchase_id';
    protected $filters = [
        'sort',
        'like',
        'in',
    ];
    protected $fillable = [
        'class_number',
        'book_number',
        'title',
        'sub_title',
        'edition_statement',
        'number_of_pages',
        'publication_year',
        'series_statement',
        'quantity',
        'image_id',
        'online_id',
        'barcode_id',
        'author_id',
        'category_id',
        'publisher_id',
        'isbn_id',
    ];
    public function coverImageForeign()
    {
        return $this->belongsTo(CoverImage::class, 'image_id');
    }
    public function bookOnlineForeign()
    {
        return $this->belongsTo(BookOnline::class, 'online_id');
    }
    public function barcodeForeign()
    {
        return $this->belongsTo(Barcode::class, 'barcode_id');
    }
    public function authorForeign()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }
    public function categoryForeign()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function publisherForeign()
    {
        return $this->belongsTo(Publishers::class, 'publisher_id');
    }
    public function isbnForeign()
    {
        return $this->belongsTo(Isbn::class, 'isbn_id');
    }


    protected $dates = ['deleted_at'];
}
