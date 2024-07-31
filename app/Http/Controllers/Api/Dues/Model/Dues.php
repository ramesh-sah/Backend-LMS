<?php

namespace App\Http\Controllers\Api\Dues\Model;

use App\Http\Controllers\Api\Book\Model\Book;
use App\Http\Controllers\Api\Employee\Model\Employee;
use App\Http\Controllers\Api\Issue\Model\Issue;
use App\Http\Controllers\Api\Member\Model\Member;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Ramsey\Uuid\Uuid;

class Dues extends Model
{
    use HasFactory, HasUuids,  SoftDeletes, FilterQueryString;

    protected $table = 'dues';
    protected $primaryKey = 'due_id';
    protected $filters = [
        'sort',
        'like',
        'in',
    ];
    protected $fillable = [
      'description',
      'amount',
      'due_status',
      'member_id',
      'employee_id',
      'book_id',
      'issue_id',

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

    public function issueForeign()
    {
        return $this->belongsTo(Issue::class, 'issue_id');
    }

    protected $dates = ['deleted_at'];

    public static function boot()
    {
        parent::boot();

       
        // Check due date for Dues when retrieving
        self::retrieved(function (Dues $dues) {
            if ($dues->due_date <= now() ) {
                $daysOverdue = now()->diffInDays($dues->due_date);
                $fineAmount = $daysOverdue * 5; // Calculate fine based on days overdue
                $dues->amount = $fineAmount; // Store the fine amount
                Log::info("Dues overdue: {$dues->due_id}, fine: {$fineAmount}");
                $dues->save(); // Save to persist the changes
            }
            if ($dues->amount ===0){
                $dues->due_status='cleared';
                $dues->save(); // Save to persist the status change
            }
        });

        Log::info('Dues model booted');
    }

}
