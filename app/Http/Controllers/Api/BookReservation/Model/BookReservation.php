<?php

namespace App\Http\Controllers\Api\BookReservation\Model;

use App\Http\Controllers\Api\Book\Model\Book;
use App\Http\Controllers\Api\BookPurchase\Model\BookPurchase;
use App\Http\Controllers\Api\Employee\Model\Employee;
use App\Http\Controllers\Api\Member\Model\Member;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Illuminate\Support\Facades\Log;

class BookReservation extends Model
{
    use HasFactory, HasUuids,  SoftDeletes, FilterQueryString;

    protected $primaryKey = 'reservation_id';
    protected $filters = [
        'sort',
        'like',
        'in',
    ];
    protected $fillable = [
        'reservation_expiry_date',
        'reservation_status',
        'member_id',
        'employee_id',
        'book_id'
    ];
    public function memberForeign()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
    public function employeeForeign()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function bookForeign()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function bookPurchaseForeign()
    {
        return $this->belongsTo(BookPurchase::class, 'purchase_id');
    }

    protected $dates = ['deleted_at'];

    public static function boot()
    {
        parent::boot();

        // Check expiry date when creating a new bookReservation
        // self::creating(function (BookReservation $bookReservation) {
        //     if (
        //         $bookReservation->reservation_expiry_date <= now() &&
        //         ($bookReservation->reservation_status === 'approved' || $bookReservation->reservation_status === 'pending' || $bookReservation->reservation_status === 'rejected')
        //     ) {
        //         $bookReservation->reservation_status = 'expired';
        //         Log::info('reservation expired during creation: ' . $bookReservation->id);
        //     }
        // });

        // Check expiry date when retrieving a bookReservation
        self::retrieved(function (BookReservation $bookReservation) {
            if (
                $bookReservation->reservation_expiry_date <= now() &&
                ($bookReservation->reservation_status === 'approved' || $bookReservation->reservation_status === 'pending')
            ) {
                $bookReservation->reservation_status = 'expired';
                Log::info('reservation expired during retrieve: ' . $bookReservation->id);
                $bookReservation->save(); // Save to persist the status change in database
            }
        });

        Log::info('BookReservation model booted');
    }
}
